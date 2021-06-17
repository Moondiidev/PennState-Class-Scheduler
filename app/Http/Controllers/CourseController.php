<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateCourseRequest;
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

        return view('courses.index', compact('courses', 'pageName'));
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
     * @param  StoreUpdateCourseRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateCourseRequest $request)
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
     */
    public function edit(Course $course)
    {
        $pageName = "Edit " . $course->title;
        $courses = Course::all();

        return view('courses.edit', compact('course', 'courses', 'pageName'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     */
    public function update(StoreUpdateCourseRequest $request, Course $course)
    {
        $course->update($request->all());
        $course->semesters()->sync($request->input('semester'));
        return back()->with('status', 'Course Updated Successfully Updated!');
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
