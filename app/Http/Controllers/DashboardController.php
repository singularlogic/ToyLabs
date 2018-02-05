<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Product;
use App\Thread;
use App\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user->profile) {
            session()->flash('warning', 'Please edit your <a href="/profile/edit">profile</a> to use the full functionality of ToyLabs.');
        }

        $products = Product::with('owner')->where([
            'owner_id'   => $user->id,
            'owner_type' => User::class,
        ])->orWhere([
            'owner_id'   => $user->organization,
            'owner_type' => Organization::class,
        ])->orderBy('updated_at', 'DESC')->get();

        $orgs    = $user->myOrganizations;
        $active  = collect();
        $archive = collect();

        foreach ($orgs as $org) {
            $active = $active->union($org->activeCollaborations);
            $active = $active->sortByDesc(function ($obj, $key) {
                return $obj->collaboratable->updated_at;
            });
            $archive = $archive->union($org->archivedCollaborations);
            $archive = $archive->sortByDesc(function ($obj, $key) {
                return $obj->collaboratable->updated_at;
            });
        }

        $data = [
            'products'    => $products,
            'is_complete' => !!$user->profile ? "true" : "false",
            'items'       => [],
            'archive'     => [],
        ];

        foreach ($active as $c) {
            $data['items'][] = [
                'id'              => $c->collaboratable->id,
                'type'            => $c->collaboratable->type,
                'title'           => $c->collaboratable->title,
                'version'         => $c->collaboratable->version,
                'updated_at'      => $c->collaboratable->updated_at->toDateTimeString(),
                'product_id'      => $c->collaboratable->product_id,
                'product_name'    => $c->collaboratable->product->title,
                'feedback_id'     => Thread::findID('feedback', $c->collaboratable, $user->organization),
                'negotiations_id' => Thread::findID('negotiation', $c->collaboratable, $user->organization),
            ];
        }

        foreach ($archive as $c) {
            $data['archive'][] = [
                'id'              => $c->collaboratable->id,
                'type'            => $c->collaboratable->type,
                'title'           => $c->collaboratable->title,
                'version'         => $c->collaboratable->version,
                'updated_at'      => $c->collaboratable->updated_at->toDateTimeString(),
                'product_id'      => $c->collaboratable->product_id,
                'product_name'    => $c->collaboratable->product->title,
                'feedback_id'     => Thread::findID('feedback', $c->collaboratable, $user->organization),
                'negotiations_id' => Thread::findID('negotiation', $c->collaboratable, $user->organization),
            ];
        }

        return view('dashboard', $data);
    }
}
