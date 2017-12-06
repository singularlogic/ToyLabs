<?php

namespace App\Http\Controllers;

use App\Collaboration;
use App\Competency;
use App\Design;
use App\Organization;
use App\OrganizationType;
use App\PaymentType;
use App\Product;
use App\Prototype;
use App\Thread;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Http\Request;

class PartnerMatchingController extends Controller
{
    protected $scales = [
        'prototype' => 0,
        'small'     => 1,
        'medium'    => 2,
        'large'     => 3,
        'xlarge'    => 4,
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, string $type, int $id)
    {
        $obj      = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        $is_owner = $this->canEdit(\Auth::user(), $obj->product);
        $data     = [
            'product'      => $obj,
            'roles'        => OrganizationType::get(),
            'competencies' => Competency::orderBy('name', 'ASC')->get(),
            'paymentTypes' => PaymentType::get(),
            'back'         => [
                'id'   => $obj->product->id,
                'type' => $type,
                'name' => ucfirst($type . 's'),
            ],
            'id'           => $id,
            'type'         => $type,
            'is_owner'     => $is_owner,
        ];
        return view('product.collaborate', $data);
    }

    public function organizationSearch(Request $request)
    {
        $query         = $request->get('query');
        $organizations = Organization::hydrate(\Searchy::organizations('name')->query($query)->get()->toArray());

        $results = [];
        foreach ($organizations as $organization) {
            $results[] = [
                'id'          => $organization->id,
                'title'       => $organization->name,
                'description' => $organization->organizationType->name,
                'url'         => '/' . $request->get('type') . '/' . $request->get('id') . '/collaborate/contact/' . $organization->id,
                // 'url'         => '/contact/' . $organization->id . '/' . $request->get('type') . '/' . $request->get('id'),
            ];
        }

        return ['results' => $results];
    }

    public function search(Request $request)
    {
        $input         = $request->input();
        $organizations = [];
        $product       = Product::findOrFail($input['product_id']);
        $productOwner  = $product->owner;

        // If the user selected a role, we ensure that only organizations with that role are returned
        if ($input['role']) {
            $organizations = Organization::where('organization_type_id', $input['role'])->with(['facilities', 'country'])->get();
        } else {
            $organizations = Organization::with(['facilities', 'country'])->get();
        }

        // Then, for each organization we calculate a score based on the filters
        foreach ($organizations as $org) {
            $score = 1.0;

            if (sizeof($input['competencies']) > 0) {
                $matched = $org->competencies->whereIn('id', $input['competencies']);
                $score   = $score * sizeof($matched) / sizeof($input['competencies']);
            }

            if ($input['scale'] && $this->scales[$input['scale']] > $this->scales[$org->production_scale]) {
                $score = 0.8 * $score / ($this->scales[$input['scale']] - $this->scales[$org->production_scale]);
            }

            if ($input['paymentDelay']) {
                if ($org->payment_in !== 'On Delivery') {
                    $days = explode(' ', $org->payment_in)[0];
                    if ($days < $input['paymentDelay']) {
                        $score = 0.5 * $score;
                    }
                } else {
                    if ($input['paymentDelay'] > 0) {
                        $score = 0.5 * $score;
                    }
                }
            }

            if ($input['paymentType']) {
                $matched = $org->paymentTypes->where('id', $input['paymentType']);
                if (sizeof($matched) === 0) {
                    $score = 0.75 * $score;
                }
            }

            $org['score'] = $score;
        }

        if ($product->owner instanceof Organization) {
            $id = $productOwner->id;
        } else {
            $id = -1;
        }
        return $organizations->reject(function ($item) use ($id) {
            return $item->id === -1; //$id;
        })->sortByDesc('score');
    }

    public function contact(Request $request, int $org_id, string $type, int $id, string $thread_type = 'negotiation')
    {
        $organization = Organization::findOrFail($org_id);
        $obj          = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        $thread       = Thread::where('type', $thread_type)
            ->where('organization_id', $org_id)
            ->where('target_id', $id)
            ->where('target_type', get_class($obj))
            ->first();

        $data = [
            'organization' => $organization,
            'id'           => $id,
            'type'         => $type,
            'name'         => $obj->title,
            'target'       => $obj,
            'thread_id'    => $thread ? $thread->id : null,
        ];

        return $data;
    }

    public function doContact(Request $request)
    {
        $input = $request->all();

        $org    = null;
        $target = null;
        try {
            // Existing Thread
            $org    = Organization::findOrFail($input['thread']['organization_id']);
            $target = $input['thread']['target_type']::findOrFail($input['thread']['target_id']);
        } catch (NotFoundHttpException $e) {
            abort(404);
        }

        if (isset($input['thread']['id'])) {
            try {
                $thread = Thread::findOrFail($input['thread']['id']);
            } catch (NotFoundHttpException $e) {
                abort(404);
            }
            $thread->activateAllParticipants();
        } else {
            // New Thread
            $thread = Thread::create($input['thread']);
        }

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id'   => \Auth::user()->id,
            'body'      => $input['message'],
        ]);

        // Sender
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id'   => \Auth::user()->id,
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients are the Owner of the Organization and the Owner of my Organization (if not myself)
        $thread->addParticipant($org->owner);
        // event(new NewMessage($org->owner, $thread->id));

        $product_owner = $target->product->owner;
        if ($product_owner !== $request->user) {
            $thread->addParticipant($product_owner);
            // event(new NewMessage($product_owner, $thread->id));
        }

        $message['user'] = \Auth::user();
        return compact('message', 'thread');
    }

    public function feedback(Request $request, string $type, int $id)
    {
        $obj  = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        $data = [
            'id'      => $id,
            'back'    => [
                'id'   => $obj->product->id,
                'type' => $type,
                'name' => ucfirst($type . 's'),
            ],
            'product' => $obj,
        ];
        return view('collaborations.feedback', $data);
    }

    public function negotiations(Request $request, string $type, int $id)
    {
        $obj            = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        $collaborations = $obj->collaborations;
        $result         = [];

        foreach ($collaborations as $collaboration) {
            $result[] = [
                'org_id'       => $collaboration->organization_id,
                'organization' => $collaboration->organization->name,
                'status'       => ucfirst($collaboration->status),
                'updated_at'   => $collaboration->updated_at->toDateTimeString(),
            ];
        }

        return $result;
    }

    public function discussions(Request $request, string $type, int $id)
    {
        $obj      = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        $feedback = $obj->feedback;
        $result   = [];

        foreach ($feedback as $n) {
            $org      = $n->organization;
            $result[] = [
                'org_id'       => $org->id,
                'organization' => $org->name,
                'updated_at'   => $n->updated_at->toDateTimeString(),
            ];
        }

        return $result;
    }

    public function addPartner(Request $request, string $type, int $id, int $org_id)
    {
        $item = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);

        Collaboration::updateOrCreate([
            'organization_id'     => $org_id,
            'collaboratable_id'   => $item->id,
            'collaboratable_type' => get_class($item),
        ], [
            'status' => 'accepted',
        ]);
    }

    protected function canEdit($user, $product)
    {
        if (is_a($product->owner, 'App\User') && $product->owner->id === $user->id) {
            return true;
        } else if (is_a($product->owner, 'App\Organization') && $user->organizations->where('id', $product->owner->id)) {
            return true;
        }

        return false;
    }
}
