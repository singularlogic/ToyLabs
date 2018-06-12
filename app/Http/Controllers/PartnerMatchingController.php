<?php

namespace App\Http\Controllers;

use App\Collaboration;
use App\Competency;
use App\Design;
use App\Events\NewMessage;
use App\Message;
use App\Notifications\NewMessageNotification;
use App\Organization;
use App\OrganizationType;
use App\PaymentType;
use App\Product;
use App\Prototype;
use App\Thread;
use Carbon\Carbon;
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
        $is_owner = \Gate::allows('owns.product', $obj->product);
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
            'is_owner'     => $is_owner ? "true" : "false",
        ];
        return view('product.collaborate', $data);
    }

    public function organizationSearch(Request $request)
    {
        $query         = $request->get('query');
        $organizations = Organization::hydrate(\Searchy::organizations('name')->query($query)->get()->toArray());

        $results = [];
        foreach ($organizations as $organization) {
            $verified  = $organization->is_verified ? '<i class="green check circle icon"></i> ' : '';
            $results[] = [
                'id'          => $organization->id,
                'title'       => $organization->name,
                'description' => $verified . $organization->organizationType->name,
                'url'         => '/' . $request->get('type') . '/' . $request->get('id') . '/collaborate/contact/' . $organization->id,
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
            $organizations = Organization::where('organization_type_id', $input['role'])
                ->with(['facilities', 'country', 'certifications'])
                ->get();
        } else {
            $organizations = Organization::with(['facilities', 'country', 'certifications'])->get();
        }

        // Then, for each organization we calculate a score based on the filters
        foreach ($organizations as $org) {
            $score = 1.0;

            if (sizeof($input['competencies']) > 0) {
                $matched = $org->competencies->whereIn('id', $input['competencies']);
                $score   = $score * sizeof($matched) / sizeof($input['competencies']);
            }

            if (isset($input['scale']) && isset($org->production_scale) && $this->scales[$input['scale']] > $this->scales[$org->production_scale]) {
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
        return response()->json($organizations->reject(function ($item) use ($id) {
            return $item->id === $id;
        })->sortByDesc('score'), 200);
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

        // Through this route, only the design/prototype owner organization can access the contact route
        if (\Gate::denies('view.product', $obj->product)) {
            abort(401, 'Unauthorized access');
        }

        $data = [
            'organization' => $organization,
            'id'           => $id,
            'type'         => $type,
            'name'         => $obj->title,
            'target'       => $obj,
            'thread_id'    => $thread ? $thread->id : null,
            'is_owner'     => \Gate::allows('owns.product', $obj->product),
        ];

        return $data;
    }

    public function doContact(Request $request, int $org_id, string $type, int $id)
    {
        $input = $request->all();

        $org    = null;
        $target = null;
        try {
            // Existing Thread
            $org = Organization::findOrFail($org_id);
            $obj = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        } catch (NotFoundHttpException $e) {
            abort(404);
        }

        if (\Gate::denies('edit.product', $obj->product)) {
            abort(401, 'Unauthorized access');
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
            $thread = Thread::create([
                'target_id'       => $id,
                'target_type'     => get_class($obj),
                'subject'         => '[' . ucfirst($type) . ": $obj->title] Negotiations with $org->name",
                'type'            => $input['thread']['type'],
                'locked'          => $input['thread']['locked'],
                'organization_id' => $org_id,
            ]);
        }

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id'   => \Auth::user()->id,
            'body'      => $input['message'],
        ]);
        $files = isset($input['files']) ? $input['files'] : [];
        foreach ($files as $file) {
            $path = storage_path('/app/' . $file['path']);
            $message->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
        }

        // Sender
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id'   => \Auth::user()->id,
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients are the Owner of the Organization and the Owner of my Organization (if not myself)
        $thread->addParticipant($org->owner->id);
        event(new NewMessage($org->owner->id, $thread->id));
        $org->owner->notify(new NewMessageNotification($org->owner, $thread));

        $product_owner = $obj->product->owner;
        if (get_class($product_owner) === Organization::class) {
            $owner = $product_owner->owner;
        } else {
            $owner = $product_owner;
        }

        if ($owner !== $request->user) {
            $thread->addParticipant($owner->id);
            event(new NewMessage($owner->id, $thread->id));
            $owner->notify(new NewMessageNotification($owner, $thread));
        }

        $message['user'] = \Auth::user();
        return compact('message', 'thread');
    }

    public function feedback(Request $request, string $type, int $id)
    {
        $obj = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);

        if (\Gate::denies('view.product', $obj->product)) {
            if (($type === 'design' && \Gate::denies('collaborate.design', $obj)) or
                $type === 'prototype' && \Gate::denies('collaborate.prototype', $obj)) {
                abort(401, 'Unauthorized access');
            }
        }

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

        if (\Gate::denies('view.product', $obj->product)) {
            if (($type === 'design' && \Gate::denies('collaborate.design', $obj)) or
                $type === 'prototype' && \Gate::denies('collaborate.prototype', $obj)) {
                abort(401, 'Unauthorized access');
            }
        }

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

        if (\Gate::denies('view.product', $obj->product)) {
            if (($type === 'design' && \Gate::denies('collaborate.design', $obj)) or
                $type === 'prototype' && \Gate::denies('collaborate.prototype', $obj)) {
                abort(401, 'Unauthorized access');
            }
        }

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
        $org  = Organization::findOrFail($org_id);

        if (\Gate::denies('owns.product', $item->product)) {
            abort(401, 'Unauthorized access');
        }

        Collaboration::updateOrCreate([
            'organization_id'     => $org_id,
            'collaboratable_id'   => $item->id,
            'collaboratable_type' => get_class($item),
        ], [
            'status' => 'accepted',
        ]);

        // Lock negotiations thread
        Thread::where('organization_id', $org_id)
            ->where('target_id', $item->id)
            ->where('target_type', get_class($item))
            ->where('type', 'negotiation')
            ->update(['locked' => true]);

        // Open feedback thread
        Thread::create([
            'subject'         => '[' . ucfirst($item->type) . ': ' . $item->title . '] Feedback from ' . $org->name,
            'organization_id' => $org_id,
            'target_id'       => $item->id,
            'target_type'     => get_class($item),
            'type'            => 'feedback',
        ]);
    }
}
