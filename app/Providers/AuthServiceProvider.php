<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider {
    /**
    * Register services.
    */

    public function register(): void {
        //
    }

    /**
    * Bootstrap services.
    */

    public function boot() {
        // $this->registerPolicies();
        Gate::define( 'admin', fn( $user )=> $user->role === 'admin' );
        Gate::define( 'bailleur', fn( $user ) => $user->role === 'bailleur' );
    }
}