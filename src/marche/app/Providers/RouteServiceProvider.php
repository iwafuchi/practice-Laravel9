<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';
    public const OWNERS_HOME = '/owners/dashboard';
    public const ADMINS_HOME = '/admins/dashboard';
    public const TESTS_HOME = '/tests/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot() {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::prefix('/')
                ->as('users.')
                ->middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('owner')
                ->as('owners.')
                ->middleware('web')
                ->group(base_path('routes/owner.php'));

            Route::prefix('admin')
                ->as('admins.')
                ->middleware('web')
                ->group(base_path('routes/admin.php'));

            Route::prefix('test')
                ->as('tests.')
                ->middleware('web')
                ->group(base_path('routes/test.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting() {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
