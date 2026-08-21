<?php

namespace App\Providers;

use App\Http\Helpers\ApiResponse;
use App\Models\Education;
use App\Models\Semester;
use App\Models\UserProfile;
use Exception;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        // TODO: change all to corresponding classes
        Relation::enforceMorphMap([
            'semester' => Semester::class,
            'education' => Education::class,
            'experience' => Semester::class,
            'project' => Semester::class,
            'honors_award' => Semester::class,
            'certification' => Semester::class,
            'user_profile' => UserProfile::class
        ]);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(300)
                ->by($request->user()->id ?: $request->ip())  // limit by user. If no user, limit by IP
                ->response(function () {
                    throw new Exception('Too many requests.', Response::HTTP_TOO_MANY_REQUESTS);
                });
        });
    }
}
