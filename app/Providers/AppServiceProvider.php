<?php

namespace App\Providers;

use App\Models\Education;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        ]);
    }
}
