<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     */
    public function index()
    {
        $pageName = "Dashboard";

        $navItems = json_encode([
                ['name'=> "Home", 'uri' => "#", 'active' => true],
                ['name'=> "View Courses", 'uri' => '/courses', 'active' => false],
                ['name'=> "Mark Courses Completed", 'uri' => '/mark-completed', 'active' => false],
                ['name'=> "Get Course Recommendations", 'uri' => '/recommendations', 'active' => false]
        ]);

        return view('home', compact('pageName', 'navItems'));
    }
}
