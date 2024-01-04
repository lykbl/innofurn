<?php

declare(strict_types=1);

use App\Http\Controllers\OAuth\OAuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', fn () => view('welcome'))->name('home');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request): void {
    $request->fulfill();

    redirect()->route('home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/logout', function () {
    Auth::logout();

    return redirect()->route('home');
});

Route::controller(OAuthController::class)
    ->prefix('/oauth')
    ->as('oauth.')
    ->group(function (): void {
        Route::get('/redirect/{type}', 'redirect')
            ->name('redirect');
        Route::get('/callback/{type}', 'callback')
            ->name('callback');
    });

Route::get('/chat-test', function () {
    return view('demo');
});

Route::get('/test', function (): void {
    // TODO remove me
});

// TODO remove me
Route::post('/login', function (Illuminate\Http\Request $request) {
    $guard = Auth::guard(Arr::first(config('sanctum.guard', 'web')));

    if ($guard->attempt(['email' => 'test04@gmail.com', 'password' => '12345678!'])) {
        $request->session()->regenerate();
    }

    return new JsonResponse(['status' => 'ok']);
});
