<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory as Socialite;
use App\Providers\AdobeSignProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Socialite $socialite): void
    {
        $socialite->extend('adobesign', function ($app) use ($socialite) {
            $config = $app['config']['services.adobesign'];

            return $socialite->buildProvider(AdobeSignProvider::class, $config);
        });
    }
}
