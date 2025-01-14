<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class SocialiteController extends Controller
{
    public function socialite()
    {
        return Socialite::driver('adobesign')->redirect();
    }

    public function callback(Request $request)
    {
        $code = $request->get('code');

        $response = Http::asForm()->post('https://api.na4.adobesign.com/oauth/v2/token', [
            'grant_type'    => 'authorization_code',
            'client_id'     => env('ADOBESIGN_CLIENT_ID'),
            'client_secret' => env('ADOBESIGN_CLIENT_SECRET'),
            'redirect_uri'  => env('ADOBESIGN_REDIRECT_URI'),
            'code'          => $code,
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            $accessToken = $responseData['access_token'];
            $refreshToken = $responseData['refresh_token'];
            $expiresIn = $response['expires_in'];
            $token_expiration_time = Carbon::now()->timestamp + $expiresIn;
            $client = new Client();
            $response = $client->get('https://api.na4.adobesign.com/api/rest/v6/users/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {

                session([
                    'ADOBESIGN_ACCESS_TOKEN' => $accessToken,
                ]);

                Cache::put('ADOBESIGN_ACCESS_TOKEN', $accessToken, now()->addSeconds($expiresIn));

                $user = json_decode($response->getBody(), true);
                $email = $user['email'];
                $firstName = $user['firstName'];
                $lastName = $user['lastName'];
                $adobeId = $user['id'];

                $userRecord = \App\Models\User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $firstName . ' ' . $lastName,
                        'adobe_id' => $adobeId,
                        'access_token' => $accessToken,
                        'expires_in' => $token_expiration_time,
                        'refresh_token' => $refreshToken,
                        'password' => '$2y$12$pWmzu/cyKUq0xrJenDJmxeS/KJOLHRZ8ZgaywILSGGqhh5mDXNieu',
                    ]
                );

                if(!Auth::check()){
                    event(new Registered($userRecord));

                    Auth::login($userRecord);
                }

                return Redirect::route('profile.edit')->with('status', 'token-updated');
            } else {
                return response()->json(['error' => 'Failed to fetch user data'], $response->getStatusCode());
            }
        } else {
            return response()->json(['error' => 'Failed to obtain access token'], $response->status());
        }
    }
}
