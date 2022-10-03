<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServicePrivider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        app()->bind('extractKeywords', function ($app, $input) {
            return myExtractKeywords($input['keyword']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        //
    }
}
