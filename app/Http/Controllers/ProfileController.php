<?php

namespace App\Http\Controllers;

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
    protected $countries = [
        'Afghanistan', 'Åland Islands', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina',
        'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda',
        'Bhutan', 'Bolivia, Plurinational State of', 'Bonaire, Sint Eustatius and Saba', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil',
        'British Indian Ocean Territory', 'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde',
        'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo',
        'Congo, the Democratic Republic of the', 'Cook Islands', 'Costa Rica', 'Côte d\'Ivoire', 'Croatia', 'Cuba', 'Curaçao', 'Cyprus', 'Czech Republic',
        'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia',
        'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern Territories', 'FYROM',
        'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guernsey', 'Guinea',
        'Guinea-Bissau', 'Guyana', 'Haiti', 'Heard Island and McDonald Islands', 'Holy See (Vatican City State)', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland',
        'India', 'Indonesia', 'Iran, Islamic Republic of', 'Iraq', 'Ireland', 'Isle of Man', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jersey', 'Jordan', 'Kazakhstan',
        'Kenya', 'Kiribati', 'Korea, Democratic People\'s Republic of', 'Korea, Republic of', 'Kuwait', 'Kyrgyzstan', 'Lao People\'s Democratic Republic', 'Latvia',
        'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macao', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali',
        'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia, Federated States of', 'Moldova, Republic of',
        'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Caledonia',
        'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau',
        'Palestinian Territory, Occupied', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico',
        'Qatar', 'Réunion', 'Romania', 'Russian Federation', 'Rwanda', 'Saint Barthélemy', 'Saint Helena, Ascension and Tristan da Cunha', 'Saint Kitts and Nevis',
        'Saint Lucia', 'Saint Martin (French part)', 'Saint Pierre and Miquelon', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe',
        'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Sint Maarten (Dutch part)', 'Slovakia', 'Slovenia', 'Solomon Islands',
        'Somalia', 'South Africa', 'South Georgia and the South Sandwich Islands', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard and Jan Mayen',
        'Swaziland', 'Sweden', 'Switzerland', 'Syrian Arab Republic', 'Taiwan, Province of China', 'Tajikistan', 'Tanzania, United Republic of', 'Thailand',
        'Timor-Leste', 'Togo', 'Tokelau', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu', 'Uganda',
        'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu',
        'Venezuela, Bolivarian Republic of', 'Viet Nam', 'Virgin Islands, British', 'Virgin Islands, U.S.', 'Wallis and Futuna', 'Western Sahara', 'Yemen',
        'Zambia', 'Zimbabwe',
    ];

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
                'countries'     => $this->countries,
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
                'countries'     => $this->countries,
                'organizations' => $organizations,
                'org_types'     => OrganizationType::orderBy('id', 'ASC')->get(),
                'personal'      => [
                    'name'      => $user->name,
                    'isNew'     => true,
                    'about'     => '',
                    'address'   => '',
                    'telephone' => '',
                    'country'   => '',
                    'facebook'  => '',
                    'twitter'   => '',
                    'linkedin'  => '',
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
            'address'   => isset($input['address']) ?: null,
            'country'   => isset($input['country']) ?: null,
            'telephone' => isset($input['telephone']) ?: null,
            'facebook'  => isset($input['facebook']) ?: null,
            'twitter'   => isset($input['twitter']) ?: null,
            'linkedin'  => isset($input['linkedin']) ?: null,
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
            'countries'    => $this->countries,
            'legalForms'   => $this->legalForms,
            'id'           => $id,
            'organization' => Organization::where('id', $id)->first(),
        ];

        if ($id > 0) {
            // TODO: Load profile from the database
        } else {
            $data['pagetitle'] = 'New Organization';
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
            return redirect('dashboard')->with('success', 'Organization updated successfully');
        } else {
            // Create Group
            $org = Organization::create($input);

            // Ask to join the group and then approve request
            $user->befriend($org);
            $org->acceptacceptFriendRequest($user);

            // TODO: Redirect to organization facilities profile creation
            return redirect('dashboard')->with('success', 'Organization created successfully');
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
            $owner = User::find($org->owner_id);
            $user->unfriend($org);
            $owner->notify(new UserLeftOrganization($user, $org));
            return response()->json([])->setStatusCode(Response::HTTP_OK);
        }

        return response()->json(['error' => 'Organization not found!'])->setStatusCode(Response::HTTP_NOT_FOUND);
    }
}
