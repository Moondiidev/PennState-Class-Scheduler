<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $navItems = json_encode([
            ['name'=> "Home", 'uri' => "/home", 'active' => false],
            ['name'=> "View Courses", 'uri' => '/courses', 'active' => false],
            ['name'=> "Mark Courses Completed", 'uri' => '/mark-completed', 'active' => false],
            ['name'=> "Get Course Recommendations", 'uri' => '/recommendations', 'active' => false]
        ]);

        View::share('navItems', $navItems);
    }
}
