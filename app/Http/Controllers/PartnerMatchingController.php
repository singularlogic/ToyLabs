<?php

namespace App\Http\Controllers;

use App\Competency;
use App\Design;
use App\Organization;
use App\OrganizationType;
use App\PaymentType;
use App\Product;
use App\Prototype;
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

    public function index(Request $request, string $type, int $id)
    {
        $obj  = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        $data = [
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
                'url'         => '/contact/' . $organization->id . '/' . $request->get('type') . '/' . $request->get('id'),
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

    public function contact(Request $request, int $org_id, string $type, int $id)
    {
        $organization = Organization::findOrFail($org_id);
        $obj          = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        $data         = [
            'organization' => $organization,
            'type'         => $type,
            'name'         => $obj->title,
        ];

        return view('collaborations.contact', $data);
    }
}
