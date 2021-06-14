<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $pageName = "Courses";
        $courses = Course::all();

        return view('courses', compact('courses', 'pageName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }

    /**
     * Show the mark course as completed form
     *
     */
    public function completedForm()
    {
        $pageName = "Mark Completed Courses";
        $courses = Course::all();
        $currentCompletedCourses = auth()->user()->completedCourses()->pluck('course_id')->toArray();

        return view('mark-completed', compact('courses', 'pageName', 'currentCompletedCourses'));
    }

    /**
     * Mark submitted classes as completed for auth user
     *
     */
    public function markAsCompleted(Request $request)
    {

        auth()->user()->completedCourses()->sync($request->input('completed'));

        return redirect(route('completedForm'))->with('status', 'Completed Courses Successfully Updated!');
    }


}
