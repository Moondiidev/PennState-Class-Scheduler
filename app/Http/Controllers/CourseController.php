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
        $pageName = "Courses";
        $courses = Course::all();

        if ( auth()->user()->isDevUser()  ) {
            $headerButtonAction = route('courses.create');
            $headerButtonText   = "Create New Course";

            return view('courses.index',
                compact('courses', 'pageName', 'headerButtonAction', 'headerButtonText'));
        }

        return view('courses.index', compact('courses', 'pageName'));

    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $pageName = "Create New Course ";
        $course = new Course();
        $courses = Course::all();

        return view('courses.create', compact('course', 'courses', 'pageName'));
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

        return redirect()->route('courses.index')->with('status', $course->title . ' Successfully Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     */
    public function show(Course $course)
    {
        $pageName = $course->title;
        $courses = Course::all();

        if ( auth()->user()->isDevUser() ) {

            $headerButtonAction = route('courses.edit', $course->id);
            $headerButtonText = "Edit Course";

            return view('courses.show', compact('course', 'courses', 'pageName',
                'headerButtonAction', 'headerButtonText'));
        }

        return view('courses.show', compact('course', 'courses', 'pageName'));
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

        return back()->with('status', 'Course Successfully Updated!');
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

        return redirect()->route('courses.index')->with('status', $course->title . ' Successfully Deleted!');
    }

    /**
     * Display class recommendations page
     */
    public function recommendations()
    {
        $pageName = "Get Course Recommendations";

        return view('recommendations.index', compact('pageName'));
    }

    /**
     * Calculate and Display recommended course results
     */
    public function recommendationResults(Request $request)
    {

        $request->validate([
            'semester' => 'required|between:1,3|numeric',
            'number_of_courses' => 'required|numeric|min:1',
        ]);

        $pageName = "Course Recommendations";

        $semester = $request->input('semester');
        $requestedNumberOfCourses = $request->input('number_of_courses');

        // courses in the selected semester that the user has not already completed
        $availableCourses = Course::getCoursesBySemester($semester)->diff($request->user()->completedCourses);

        // find courses to suggest
        $suggestedCourses = $this->getSuggestedCourses($availableCourses);

        // find and add any available concurrent courses based on suggested courses above
        $suggestedCourses =
            $this->addCoursesThatCanBeRecommendedAsConcurrent($suggestedCourses, $semester)
                 ->take($requestedNumberOfCourses);

        $warnings = $this->getConcurrentWarnings($suggestedCourses, $semester);

        if ($requestedNumberOfCourses > $suggestedCourses->count()) {
           $this->lessThanExpectedCoursesWarning($warnings);
        }

        return view('recommendations.show',
            compact('pageName', 'suggestedCourses', 'warnings', 'semester', 'requestedNumberOfCourses'));

    }


    private function getSuggestedCourses($availableCourses)
    {
        return $availableCourses->filter(function ($value) {

            return ($value->prerequisites == null && $value->concurrents == null)
                   ||
                   (
                       ( $value->prerequisites && ! array_diff( $value->prerequisites, auth()->user()->completedCourses()->pluck('course_id')->toArray()) )
                       &&
                       ( $value->concurrents && ! array_diff( $value->concurrents, auth()->user()->completedCourses()->pluck('course_id')->toArray()) )
                   )
                   ||
                   (
                       ( ! $value->prerequisites )
                       &&
                       ( $value->concurrents && ! array_diff( $value->concurrents, auth()->user()->completedCourses()->pluck('course_id')->toArray()) )
                   )
                   ||
                   (
                       ( $value->prerequisites && ! array_diff( $value->prerequisites, auth()->user()->completedCourses()->pluck('course_id')->toArray()) )
                       &&
                       ( ! $value->concurrents )
                   );

        })->sortByDesc('semester_specific')->sortByDesc('prerequisites_for_count');
    }

    private function addCoursesThatCanBeRecommendedAsConcurrent($suggestedCourses, $semester)
    {
        // insert concurrent course directly after the course it is concurrent with
        foreach ($this->findCoursesThatCanBeRecommendedAsConcurrent($suggestedCourses, $semester) as $course) {
            $index = $suggestedCourses->search(Course::find($course->concurrents[0]));
            $suggestedCourses->splice($index, 0, [$course]);
        }

        return $suggestedCourses;
    }

    private function findCoursesThatCanBeRecommendedAsConcurrent($suggestedCourses, $semester)
    {
        return Course::getCoursesWithConcurrents($semester)->filter(function ($course)  use ($suggestedCourses) {
            return  ! array_diff( $course->concurrents, $suggestedCourses->pluck('id')->toArray());
        });
    }

    private function getConcurrentWarnings($suggestedCourses, $semester)
    {
        $includedConcurrentCourses = $suggestedCourses
            ->intersect($this->findCoursesThatCanBeRecommendedAsConcurrent($suggestedCourses, $semester));

        $warnings = [];

        foreach ($includedConcurrentCourses as $key => $course) {
            $warnings[$key] = $course->abbreviation . " can only be taken as a concurrent if you also take " .
                              Course::find($course->concurrents[0])->abbreviation . "!";
        }

        return $warnings;
    }

    private function lessThanExpectedCoursesWarning(& $warnings)
    {
        return array_push($warnings, "We could not find enough available courses to meet
            the number of courses you requested due to limitation of course offerings and prerequisites.");
    }


}
