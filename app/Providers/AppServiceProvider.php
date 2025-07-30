<?php

namespace App\Providers;

use App\Models\Cashbox;
use App\Models\User;
use App\Observers\CashboxObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        User::observe(UserObserver::class); // this help me delet token when I delete specific user
        // Cashbox::observe(CashboxObserver::class);
    }
}
