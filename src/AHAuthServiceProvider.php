<?php

namespace FrentePatriaGrande\AHAuth;

use FrentePatriaGrande\AHAuth\Socialite\ArgentinaHumanaSocialiteProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Contracts\Factory;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AHAuthServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('ah-auth')
            ->hasMigration('add_ah_auth_columns_to_users_table');
    }

    public function packageBooted()
    {
        // Add ah base request to Http client
        Http::macro('ah', function () {
            $pendingRequest = Http::baseUrl(config('services.argentina-humana.base_url').'/api');

            if ($user = Auth::user()) {
                $pendingRequest->withHeader('Authorization', "Bearer {$user->getAhAccessToken()}");
            }

            return $pendingRequest;
        });

        // Add AH provider to socialite
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('argentina-humana', function () use ($socialite) {
            $config = config('services.argentina-humana');

            return $socialite->buildProvider(ArgentinaHumanaSocialiteProvider::class, $config);
        });
    }
}
