<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// ✅ هذا هو الـ import المطلوب
use Illuminate\Auth\Notifications\ResetPassword;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any authentication / authorization services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
        $broker = 'users'; // غيّره لو عندك broker آخر
        return route('password.reset', [
            'broker' => $broker,
            'token'  => $token,
        ]) . '?email=' . urlencode($user->email);
    });
    }
}
