<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Profile;
use Illuminate\Http\Request;
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

    protected $legalForms = [];

    public function showPersonalProfile()
    {
        $organizations = Organization::orderBy('name', 'ASC')->pluck('id', 'name');
        $user          = Auth::user();

        if ($user->profile) {
            $user->profile['name'] = $user->name;
            $data                  = [
                'countries'     => $this->countries,
                'organizations' => $organizations,
                'personal'      => $user->profile,
                'professional'  => [
                    'role'         => $user->roles()->pluck('name')[0],
                    'organization' => $user->organization ? $user->organizations[0]->id : -1,
                ],
            ];
        } else {
            $data = [
                'countries'     => $this->countries,
                'organizations' => $organizations,
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
                    'role'         => $user->roles()->pluck('name')[0],
                    'organization' => -1, // Users with no personal profile don't belong to an Organization
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
            'address'   => $input['address'],
            'country'   => $input['country'],
            'telephone' => $input['telephone'],
            'facebook'  => $input['facebook'],
            'twitter'   => $input['twitter'],
            'linkedin'  => $input['linkedin'],
        ]);
        if (!$user->hasRole($input['role'])) {
            $user->removeRole('end_user');
            $user->assignRole($input['role']);
        }

        $user->name = $input['name'];
        $user->save();

        if (isset($input['has_organization'])) {
            if (isset($input['state'])) {
                if ($input['state'] === 'new') {
                    return redirect()->route('organization.edit', ['id' => 0])->with('info', 'Personal profile updated successfully.');
                } else {
                    return redirect('dashboard')->with('warning', 'Check function!');
                    // TODO: Ask to join the Organization
                }
            }
        }

        return redirect('dashboard')->with('success', 'Personal profile updated successfully.');
    }

    public function showMyOrganizationProfile()
    {
        $user = Auth::user();
        if ($user->organization) {
            return $this->showOrganizationProfile($user->organization->id);
        }

        return $this->showOrganizationProfile(0);
    }

    public function showOrganizationProfile($id)
    {
        if ($id > 0) {
            $data = [
                'countries'  => $this->countries,
                'legalForms' => $this->legalForms,
            ];
            // TODO: Load profile from the database
        } else {
            $data = [
                'countries'  => $this->countries,
                'legalForms' => $this->legalForms,
                'pagetitle'  => 'New Organization',
            ];
        }

        return view('profile.organization', $data);
    }

    public function saveOrganizationProfile(Request $request, $id)
    {

    }
}
