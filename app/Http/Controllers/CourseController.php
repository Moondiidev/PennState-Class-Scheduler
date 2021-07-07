<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateCourseRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('dev.users.only')->except(['index', 'completedForm', 'markAsCompleted']);
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
//        $pageName = "Courses";
//        $courses = Course::all();
//
//        if ( auth()->user()->isDevUser()  ) {
//            $headerButtonAction = route('courses.create');
//            $headerButtonText   = "Create New Course";
//
//            return view('courses.index', compact('courses', 'pageName', 'headerButtonAction', 'headerButtonText'));
//        }
//
//        return view('courses.index', compact('courses', 'pageName'));

        return response()->json([
            'courses' => Course::all(),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
//        $pageName = "Create New Course ";
//        $course = new Course();
//        $courses = Course::all();
//
//        return view('courses.create', compact('course', 'courses', 'pageName'));

        return response()->json([
            'courses' => Course::all(),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUpdateCourseRequest  $request
     *
     */
    public function store(StoreUpdateCourseRequest $request)
    {
        $course = Course::create($request->all());
        $course->semesters()->sync($request->input('semester'));

//        return redirect()->route('courses.index')->with('status', $course->title . ' Successfully Created!');

        return response()->json([
            'message' =>  $course->title . ' Successfully Created!',
            'course' =>  $course,
            'courses' => Course::all(),
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     */
    public function show(Course $course)
    {
//        $pageName = $course->title;
//        $courses = Course::all();
//
//        if ( auth()->user()->isDevUser() ) {
//
//            $headerButtonAction = route('courses.edit', $course->id);
//            $headerButtonText = "Edit Course";
//
//            return view('courses.show', compact('course', 'courses', 'pageName',
//                'headerButtonAction', 'headerButtonText'));
//        }
//
//        return view('courses.show', compact('course', 'courses', 'pageName'));

        return response()->json([
            'course' =>  $course,
            'courses' => Course::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     */
    public function edit(Course $course)
    {
//        $pageName = "Edit " . $course->title;
//        $courses = Course::all();
//
//        return view('courses.edit', compact('course', 'courses', 'pageName'));

        return response()->json([
            'course' =>  $course,
            'courses' => Course::all(),
        ]);
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

//        return back()->with('status', 'Course Successfully Updated!');

        return response()->json([
            'message' =>  $course->title . ' Successfully Updated!',
            'course' =>  $course,
            'courses' => Course::all(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     *
     */
    public function destroy(Course $course)
    {
        Course::removeDeletedCourseFromPrerequisites($course->id);
        Course::removeDeletedCourseFromConcurrents($course->id);

        $course->delete();

//        return redirect()->route('courses.index')->with('status', $course->title . ' Successfully Deleted!');

        return response()->json([
            'message' =>  $course->title . ' Successfully Deleted',
            'course' =>  $course,
            'courses' => Course::all(),
        ]);


    }

    /**
     * Show the mark course as completed form
     */
    public function completedForm()
    {
//        $pageName = "Mark Completed Courses";
//        $courses = Course::all();
//        $currentCompletedCourses = auth()->user()->completedCourses()->pluck('course_id')->toArray();
//
//        return view('mark-completed', compact('courses', 'pageName', 'currentCompletedCourses'));

        return response()->json([
            'currentCompletedCourses' =>  auth()->user()->completedCourses(),
            'courses' => Course::all()
        ]);

    }

    /**
     * Mark submitted classes as completed for auth user
     */
    public function markAsCompleted(Request $request)
    {
        auth()->user()->completedCourses()->sync($request->input('completed'));

//        return redirect(route('completedForm'))->with('status', 'Completed Courses Successfully Updated!');

        return response()->json([
            'message' =>  'Completed Courses Successfully Marked!',
            'currentCompletedCourses' =>  auth()->user()->completedCourses(),
            'courses' => Course::all(),
        ]);
    }

    /**
     * Display class recommendations page
     */
    public function recommendations()
    {
//        $pageName = "Get Course Recommendations";
//
//
//        return view('recommendations', compact('pageName'));

        return response()->json([
            'message' =>  'Working on it...',
        ]);


    }

    /**
     * Calculate and Display recommended course results
     */
    public function recommendationResults(Request $request)
    {
        return response()->json([
            'message' =>  'Working on it...',
        ]);
    }



}
