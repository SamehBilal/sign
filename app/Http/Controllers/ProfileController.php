<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Refresh the user's token.
     */
    public function refresh(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        $response = Http::asForm()->post('https://api.na4.adobesign.com/oauth/v2/refresh', [
            'grant_type'    => 'refresh_token',
            'client_id'     => env('ADOBESIGN_CLIENT_ID'),
            'client_secret' => env('ADOBESIGN_CLIENT_SECRET'),
            'refresh_token' => $request->refresh_token,
        ]);


        $responseData = $response->json();
        $token_expiration_time = Carbon::now()->timestamp + $responseData['expires_in'];
        $request->user()->access_token  = $responseData['access_token'];
        $request->user()->expires_in    = $token_expiration_time;

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'token-refreshed');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
