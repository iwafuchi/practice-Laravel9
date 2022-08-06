<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware {
    protected $userRoute = 'user.login';
    protected $ownerRoute = 'owners.login';
    protected $adminRoute = 'admins.login';
    protected $testRoute = 'tests.login';
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request) {
        if (!$request->expectsJson()) {
            if (Route::is('owners.*')) {
                return route($this->ownerRoute);
            }

            if (Route::is('admins.*')) {
                return route($this->adminRoute);
            }

            if (Route::is('tests.*')) {
                return route($this->testRoute);
            }

            return route($this->userRoute);
        }
    }
}
