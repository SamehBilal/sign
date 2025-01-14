<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\AdobeSignController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\TalentController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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

Route::get('/auth/adobesign/redirect', [SocialiteController::class, 'socialite'])->name('adobe.login');

Route::get('/auth/adobesign/callback', [SocialiteController::class, 'callback'])->name('adobe.callback');

Route::middleware('auth')->group(function () {

    Route::post('/upload-csv', [FileUploadController::class, 'uploadCsv'])->name('upload.csv');
    Route::post('/upload-template', [FileUploadController::class, 'uploadTemplate'])->name('upload.template');
    Route::post('/upload-agreement', [FileUploadController::class, 'uploadAgreement'])->name('upload.agreement');

    Route::get('/dashboard', [AgreementController::class, 'dashboard'])->name('dashboard');
    Route::post('/send-agreement', [AdobeSignController::class, 'sendAgreement'])->name('sendAgreement');
    Route::get('/agreements', [AgreementController::class, 'index'])->name('agreements.index');
    Route::get('/templates', [LibraryController::class, 'index'])->name('templates.index');
    Route::post('/templates', [LibraryController::class, 'store'])->name('templates.store');
    Route::post('/templates/{id}', [LibraryController::class, 'show'])->name('templates.show');
    Route::post('/templates/{id}/events', [LibraryController::class, 'events'])->name('templates.show');
    Route::post('/agreements', [AgreementController::class, 'store'])->name('agreements.store');
    Route::post('/agreements/{id}', [AgreementController::class, 'show'])->name('agreements.show');
    Route::post('/agreements/{id}/events', [AgreementController::class, 'events'])->name('agreements.show');
    Route::get('/talents', [TalentController::class, 'index'])->name('talents.index');
    Route::post('/send-bulk-agreements', [AdobeSignController::class, 'sendBulkAgreements'])->name('send.bulk.agreements');
    Route::get('/agreement-status/{agreementId}', [AdobeSignController::class, 'checkAgreementStatus']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-refresh', [ProfileController::class, 'refresh'])->name('profile.refresh');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
