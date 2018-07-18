<?php

namespace App\Http\Controllers;

use App\Award;
use App\Certification;
use App\Competency;
use App\Country;
use App\Design;
use App\Facility;
use App\GeographicalMarket;
use App\Mail\DeleteAccountRequested;
use App\Notifications\UserLeftOrganization;
use App\Organization;
use App\OrganizationType;
use App\PaymentType;
use App\Product;
use App\Profile;
use App\Prototype;
use App\ToyCategory;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{
    protected $legalForms = [
        'Freelancer', 'Sole Trader', 'Trading Partnership', 'Limited Company (LTD)', 'Economic Association', 'Société Anonyme (S.A)',
    ];

    public function showPersonalProfile()
    {
        $organizations = Organization::orderBy('name', 'ASC')->get();
        $user          = Auth::user();

        if ($user->profile) {
            $user->profile['name'] = $user->name;
            $data                  = [
                'countries'     => Country::orderBy('name', 'ASC')->get(),
                'organizations' => $organizations,
                'org_types'     => OrganizationType::orderBy('id', 'ASC')->get(),
                'personal'      => $user->profile,
                'professional'  => [
                    'role'          => $user->roles()->where('name', '!=', 'admin')->pluck('name')[0],
                    'organizations' => $user->memberships,
                    'pending'       => $user->pendingMemberships,
                ],
            ];
        } else {
            $data = [
                'countries'     => Country::orderBy('name', 'ASC')->get(),
                'organizations' => $organizations,
                'org_types'     => OrganizationType::orderBy('id', 'ASC')->get(),
                'personal'      => [
                    'name'       => $user->name,
                    'isNew'      => true,
                    'about'      => '',
                    'address'    => '',
                    'telephone'  => '',
                    'country_id' => -1,
                    'facebook'   => '',
                    'twitter'    => '',
                    'linkedin'   => '',
                ],
                'professional'  => [
                    'role'          => $user->roles()->pluck('name')[0],
                    'organizations' => [], // Users with no personal profile don't belong to an Organization
                    'pending'       => [], // neither have pending requests
                ],
            ];
        }

        return view('profile.personal', $data);
    }

    public function savePersonalProfile(Request $request)
    {
        $input = $request->all();
        $user  = Auth::user();

        $profile = Profile::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'address'    => isset($input['address']) ? $input['address'] : null,
            'country_id' => isset($input['country_id']) ? (int) $input['country_id'] : null,
            'telephone'  => isset($input['telephone']) ? $input['telephone'] : null,
            'facebook'   => isset($input['facebook']) ? $input['facebook'] : null,
            'twitter'    => isset($input['twitter']) ? $input['twitter'] : null,
            'linkedin'   => isset($input['linkedin']) ? $input['linkedin'] : null,
        ]);
        if (isset($input['role']) && !$user->hasRole($input['role'])) {
            $user->removeRole('end_user');
            $user->assignRole($input['role']);
        }

        $user->name = $input['name'];
        $user->save();

        if ($input['createOrganization'] == true) {
            return redirect()->route('organization.edit.mine');
        }

        if (isset($input['joinOrg'])) {
            $user = Auth::user();
            $org  = Organization::find($input['joinOrg']);

            if ($org) {
                $user->befriend($org);
            }
        }

        if (isset($input['has_organization'])) {
            if (isset($input['state'])) {
                if ($input['state'] === 'new') {
                    return redirect()->route('organization.edit.mine')->with('info', 'Personal profile updated successfully.');
                }
            }
        }

        return redirect('dashboard')->with('success', 'Personal profile updated successfully.');
    }

    public function showMyOrganizationProfile()
    {
        $user = Auth::user();

        if (count($user->myOrganizations) > 0) {
            return $this->showOrganizationProfile($user->myOrganizations[0]->id);
        }

        return $this->showOrganizationProfile(0);
    }

    public function showOrganizationProfile($id)
    {
        $user = Auth::user();
        $org  = Organization::find($id);
        if ($org && $org->owner_id !== $user->id) {
            return redirect()->route('organization.profile', ['id' => $id])->with('error', 'You are not the owner of this organization');
        }

        if ($user->roles()->pluck('id')[0] == 6) {
            return redirect('dashboard')->with('error', 'Only professional users can create/join an organization');
        }

        $data = [
            'countries'          => Country::orderBy('name', 'ASC')->get(),
            'legalForms'         => $this->legalForms,
            'id'                 => $id,
            'organization'       => $org,
            'facilities'         => Facility::where('organization_id', $id)->get(),
            'competencies'       => Competency::orderBy('name', 'ASC')->get(),
            'markets'            => GeographicalMarket::get(),
            'categories'         => ToyCategory::where('title', '<>', 'Other')->orderBy('title')->get(),
            'services'           => $org ? $org->services : [],
            'paymentTypes'       => PaymentType::orderBy('name', 'ASC')->get(),
            'awardTypes'         => Award::orderBy('name', 'ASC')->get(),
            'certificationTypes' => Certification::orderBy('name', 'ASC')->get(),
            'certifications'     => $org ? $org->certifications : [],
            'awards'             => $org ? $org->awards : [],
        ];

        if ($id > 0) {
            // TODO: Load profile from the database
        } else {
            $data['pagetitle'] = 'Create Organization Profile';
        }

        return view('profile.organization', $data);
    }

    public function saveOrganizationProfile(Request $request)
    {
        $input = $request->all();
        $id    = $input['id'];
        $user  = Auth::user();

        if ($id > 0) {
            // Update General information
            $organization = Organization::find($id);
            $general      = $request->only([
                'name', 'legal_name', 'legal_form', 'address', 'po_box', 'postal_code', 'country_id',
                'twitter', 'facebook', 'instagram', 'phone', 'fax', 'website_url', 'description', 'city',
            ]);

            if (\Gate::denies('organization.owner', $organization)) {
                abort(401, 'Unauthorized access');
            }

            // Update Organization
            if ($general['legal_name'] === null || $general['legal_name'] === '') {
                $general['legal_name'] = $general['name'];
            }
            Organization::where([
                'id' => $id,
            ])->update($general);

            // Update Facilities
            Facility::where('organization_id', $id)->delete();
            $facilities = json_decode($input['facilities'], true);
            foreach ($facilities as $facility) {
                Facility::create([
                    'name'            => $facility['name'],
                    'city_id'         => $facility['city']['id'],
                    'organization_id' => $id,
                ]);
            }

            // Update Services
            $services = json_decode($input['services'], true);
            $org      = Organization::find($id);
            $org->competencies()->sync($services['competencies']);
            $org->expertise()->sync($services['expertise']);
            $org->paymentTypes()->sync($services['payment_types']);
            $org->markets()->sync($services['markets']);
            $org->payment_in       = $services['payment_in'];
            $org->production_scale = $services['production_scale'];

            // Update Awards & Certifications
            $awards         = json_decode($input['awards'], true);
            $certifications = json_decode($input['certifications'], true);
            $org->awards()->sync($awards);
            $org->certifications()->sync($certifications);

            $org->save();

            return redirect('dashboard')->with('success', 'Organization profile updated successfully');
        } else {
            // Set Owner and Organization type
            $input['owner_id']             = $user->id;
            $input['organization_type_id'] = OrganizationType::whereIn('role_id', $user->roles()->pluck('id'))->first()->id;
            if ($input['legal_name'] === null || $input['legal_name'] === '') {
                $input['legal_name'] = $input['name'];
            }
            unset($input['_token']);

            // Create Group
            $org = Organization::create($input);

            // Ask to join the group and then approve request
            $user->befriend($org);
            $org->acceptFriendRequest($user);

            // Add Facilities
            $facilities = json_decode($input['facilities'], true);
            foreach ($facilities as $facility) {
                Facility::create([
                    'name'            => $facility['name'],
                    'city_id'         => $facility['city']['id'],
                    'organization_id' => $org->id,
                ]);
            }

            return redirect('dashboard')->with('success', 'Organization profile created successfully');
        }
    }

    public function joinOrganization(Request $request, $id)
    {
        $user = Auth::user();
        $org  = Organization::find($id);

        if ($org) {
            $user->befriend($org);
            return response()->json([])->setStatusCode(Response::HTTP_OK);
        }

        return response()->json(['error' => 'Organization not found!'])->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    public function leaveOrganization(Request $request, $id)
    {
        $user = Auth::user();
        $org  = Organization::find($id);

        if ($org) {
            // Leave Organization
            $user->unfriend($org);
            $user->organizations()->detach($org);

            // Notify Organization owner
            $owner = User::find($org->owner_id);
            $owner->notify(new UserLeftOrganization($user, $org));
            return response()->json([])->setStatusCode(Response::HTTP_OK);
        }

        return response()->json(['error' => 'Organization not found!'])->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    public function organizationProfile(Request $request, $id)
    {
        try {
            $org = Organization::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Organization not found');
        }

        $data = [
            'pagetitle'    => $org->name,
            'organization' => $org,
            'competencies' => Competency::orderBy('name', 'asc')->get(),
        ];

        return view('profile.public', $data);
    }

    public function organizations()
    {
        $organizations = Organization::orderBy('name', 'ASC')->get();
        $data          = [
            'organizations' => $organizations,
        ];

        return view('profile.organizations', $data);
    }

    public function json(int $id)
    {
        try {
            $org = Organization::findOrFail($id);
            return $org;
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    public function confirmDeleteProfile()
    {
        $user          = \Auth::user();
        $organizations = $user->myOrganizations;

        $public_products = Product::where([
            'owner_id'   => $user->id,
            'owner_type' => User::class,
            'is_public'  => true,
        ])->count();

        $public_designs = Design::with('product')->whereHas('product', function ($q) use ($user) {
            $q->where([
                'owner_id'   => $user->id,
                'owner_type' => User::class,
            ]);
        })->where([
            'is_public' => true,
        ])->count();

        $public_prototypes = Prototype::with('product')->whereHas('product', function ($q) use ($user) {
            $q->where([
                'owner_id'   => $user->id,
                'owner_type' => User::class,
            ]);
        })->where([
            'is_public' => true,
        ])->count();

        $hasErrors = ($public_products + $public_designs + $public_prototypes + count($organizations)) > 0;

        $data = [
            'hasErrors'         => $hasErrors,
            'has_organizations' => true,
            'public_products'   => $public_products,
            'public_designs'    => $public_designs,
            'public_prototypes' => $public_prototypes,
            'organizations'     => $organizations,
        ];

        return view('profile.delete', $data);
    }

    public function deleteProfile()
    {
        $user         = \Auth::user();
        $user->status = 'suspended';
        $user->save();

        Mail::to('admin@toylabs.eu')->send(new DeleteAccountRequested($user));

        \Auth::logout();

        return response()->json([], 200);
    }
}
