<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware {
    protected $userRoot = 'user.login';
    protected $ownerRoot = 'owners.login';
    protected $adminRoot = 'admins.login';
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request) {
        if (!$request->expectsJson()) {
            if (Route::is('owners.*')) {
                return route($this->ownerRoot);
            }
            if (Route::is('admins.*')) {
                return route($this->adminRoot);
            }
            return route($this->userRoot);
        }
    }
}
