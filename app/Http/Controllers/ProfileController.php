<?php

namespace App\Http\Controllers;

use App\Country;
use App\Notifications\UserLeftOrganization;
use App\Organization;
use App\OrganizationType;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
                    'role'          => $user->roles()->pluck('name')[0],
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
        $data = [
            'countries'    => Country::orderBy('name', 'ASC')->get(),
            'legalForms'   => $this->legalForms,
            'id'           => $id,
            'organization' => Organization::where('id', $id)->first(),
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
        $input                         = $request->all();
        $id                            = $input['id'];
        $user                          = Auth::user();
        $input['owner_id']             = $user->id;
        $input['organization_type_id'] = OrganizationType::where('role_id', $user->roles()->pluck('id')[0])->first()->id;
        unset($input['_token']);

        if ($id > 0) {
            // Update
            Organization::where('id', $id)->update($input);
            return redirect('dashboard')->with('success', 'Organization profile updated successfully');
        } else {
            // Create Group
            $org = Organization::create($input);

            // Ask to join the group and then approve request
            $user->befriend($org);
            $org->acceptacceptFriendRequest($user);

            // TODO: Redirect to organization facilities profile creation
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
}
