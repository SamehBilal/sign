<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\AdobeSignController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\TalentController;

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

Route::get('/auth/adobesign/redirect', [SocialiteController::class, 'socialite'])->name('adobe.login');

Route::get('/auth/adobesign/callback', [SocialiteController::class, 'callback'])->name('adobe.callback');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-refresh', [ProfileController::class, 'refresh'])->name('profile.refresh');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
