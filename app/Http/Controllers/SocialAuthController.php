<?php

namespace App\Http\Controllers;

use App\SocialAccount;
use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{

    public function getOrCreateUser($provider, ProviderUser $providerUser)
    {
        $account = SocialAccount::whereProvider($provider)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            // Check if avatar changed, and update it
            if ($account->user->avatar !== $providerUser->getAvatar()) {
                $account->user->avatar = $providerUser->getAvatar();
                $account->user->save();
            }

            // If user used this social account before, return the user
            return $account->user;
        } else {
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider'         => $provider,
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();
            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name'  => $providerUser->getName(),
                ]);

                $user->assignRole('end_user');
            }

            if ($user->avatar !== $providerUser->getAvatar()) {
                $user->avatar = $providerUser->getAvatar();
                $user->save();
            }

            $account->user()->associate($user);
            $account->save();
            return $user;
        }
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $request, $provider)
    {
        if (!$request->has('code') || $request->has('denied')) {
            return redirect('/login');
        }

        $user = $this->getOrCreateUser($provider, Socialite::driver($provider)->user());
        auth()->login($user);

        return redirect()->to('/dashboard');
    }

    public function passportCallback(Request $request, $provider)
    {
        if (!$request->has('code') || $request->has('denied')) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        } else {
            $user = $this->getOrCreateUser($provider, Socialite::driver($provider)->user());
            auth()->login($user);
            $success['token'] = $user->createToken('Toylabs')->accessToken;
            return response()->json([
                'success' => $success,
            ], 200);
        }
    }
}
