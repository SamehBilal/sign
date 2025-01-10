<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\AdobeSignController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\TalentController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/upload-csv', [FileUploadController::class, 'uploadCsv'])->name('upload.csv');
Route::post('/prepare-agreements', [AgreementController::class, 'prepareAgreements']);

Route::post('/send-agreement', [AdobeSignController::class, 'sendAgreement'])->name('sendAgreement');
Route::get('/agreements', [AgreementController::class, 'index'])->name('agreements.index');
Route::get('/talents', [TalentController::class, 'index'])->name('talents.index');
Route::post('/send-bulk-agreements', [AdobeSignController::class, 'sendBulkAgreements'])->name('send.bulk.agreements');
Route::get('/agreement-status/{agreementId}', [AdobeSignController::class, 'checkAgreementStatus']);
Route::post('/cancel-agreement/{agreementId}', [AdobeSignController::class, 'cancelAgreement']);

Route::get('/auth/adobesign/redirect', function () {
    return Socialite::driver('adobesign')->redirect();
})->name('adobe.login');

Route::get('/auth/adobesign/callback', function (Request $request) {
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
        $client = new Client();
        $response = $client->get('https://api.na4.adobesign.com/api/rest/v6/users/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            session(['ADOBESIGN_ACCESS_TOKEN' => $accessToken]);
            Cache::put('ADOBESIGN_ACCESS_TOKEN', $accessToken, now()->addMinutes(60)); // expires in 1 hour
            $user = json_decode($response->getBody(), true);
            $email = $user['email'];
            $firstName = $user['firstName'];
            $lastName = $user['lastName'];
            $adobeId = $user['id'];

            // Use updateOrCreate to insert or update the user
            $userRecord = \App\Models\User::updateOrCreate(
                // Match the user by email
                ['email' => $email],
                // Update or create with the following fields
                [
                    'name' => $firstName . ' ' . $lastName, // Combine first and last name
                    'adobe_id' => $adobeId,
                    'password' => Hash::make("social@123456"), // Generate a random password for new users
                ]
            );

            event(new Registered($user));

            Auth::login($userRecord);

            return redirect(RouteServiceProvider::HOME);
        } else {
            return response()->json(['error' => 'Failed to fetch user data'], $response->getStatusCode());
        }
    } else {
        return response()->json(['error' => 'Failed to obtain access token'], $response->status());
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
