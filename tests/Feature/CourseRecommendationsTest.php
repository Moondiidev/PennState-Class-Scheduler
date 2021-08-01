<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Database\Seeders\SemesterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseRecommendationsTest extends TestCase
{
    protected $seed = true;
    protected $seeder = SemesterSeeder::class;

    use RefreshDatabase;

    private $user;
    private $courseOne;
    private $courseTwo;
    private $courseThree;
    private $courseFour;
    private $courseFive;
    private $courseSix;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['name' => 'Jessica Tester', 'email' => 'jessica@psu.edu']);


        $this->courseOne = Course::factory()->create(
            ['title' => 'Test Course One', 'abbreviation' => 'Test 221', 'type' => 'Test',
             'semester_specific' => false, 'prerequisites_for_count' => 2
            ]);
        $this->courseOne->semesters()->attach([1, 2]);


        $courseTwoPrerequisites = [$this->getCourseId(1)];
        $this->courseTwo = Course::factory()->create(
            ['title' => 'Test Course Two', 'abbreviation' => 'Test 311', 'type' => 'Test',
             'semester_specific' => false, 'prerequisites_for_count' => 2,
                'prerequisites' => $courseTwoPrerequisites]);
        $this->courseTwo->semesters()->attach([1, 2]);


        $courseThreePrerequisites = [$this->getCourseId(2)];
        $courseThreeConcurrents = [$this->getCourseId(2)];
        $this->courseThree = Course::factory()->create(
            ['title' => 'Test Course Three', 'abbreviation' => 'Test 432', 'type' => 'Test',
             'semester_specific' => false, 'prerequisites_for_count' => 1,
             'prerequisites' => $courseThreePrerequisites, 'concurrents' => $courseThreeConcurrents]);
        $this->courseThree->semesters()->attach([1, 3]);


        $courseFourPrerequisites = [$this->getCourseId(3)];
        $this->courseFour = Course::factory()->create(
            ['title' => 'Test Course Four', 'abbreviation' => 'Test 512', 'type' => 'Test',
             'semester_specific' => true, 'prerequisites_for_count' => 0,
             'prerequisites' => $courseFourPrerequisites]);
        $this->courseFour->semesters()->attach([1]);


        $courseFivePrerequisites = [$this->getCourseId(1)];
        $this->courseFive = Course::factory()->create(
            ['title' => 'Test Course Five', 'abbreviation' => 'Test 661', 'type' => 'Test',
             'semester_specific' => true, 'prerequisites_for_count' => 1,
             'prerequisites' => $courseFivePrerequisites]);
        $this->courseFive->semesters()->attach([1]);


        $courseSixPrerequisites = [$this->getCourseId(2), $this->getCourseId(5)];
        $this->courseSix = Course::factory()->create(
            ['title' => 'Test Course Six', 'abbreviation' => 'Test 654', 'type' => 'Test',
             'semester_specific' => false, 'prerequisites_for_count' => 0,
             'prerequisites' => $courseSixPrerequisites]);
        $this->courseSix->semesters()->attach([2, 3]);

    }

    /**  @test */
    public function correct_courses_are_not_recommended_without_entering_the_number_of_courses()
    {

        $data = ["semester" => 1, 'number_of_courses' => null];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertRedirect(route('recommendations'));
        $response->assertSessionHasErrorsIn('number_of_courses');

    }

    /**  @test */
    public function correct_courses_are_recommended_1()
    {
        // no completed courses

        // only one course to recommend (course 1) b/c of semester and pre-req limits

        $data = ["semester" => 1, 'number_of_courses' => 2];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertSuccessful();
        $response->assertSee("Test Course One");
        $response->assertSee("We could not find enough available courses to meet
            the number of courses you requested due to limitation of course offerings and prerequisites.");

    }

    /**  @test */
    public function correct_courses_are_recommended_2()
    {
        // completed course one
        $this->user->completedCourses()->sync([$this->getCourseId(1)]);

        // only one course to recommend (course 2) b/c of semester and pre-req limits

        $this->user->fresh();

        $data = ["semester" => 2, 'number_of_courses' => 1];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertSuccessful();
        $response->assertSee("Test Course Two");
        $response->assertDontSee("We could not find enough available courses to meet
            the number of courses you requested due to limitation of course offerings and prerequisites.");

    }

    /**  @test */
    public function correct_courses_are_recommended_3()
    {
        // completed course one
        $this->user->completedCourses()->sync([$this->getCourseId(1)]);

        // two courses can be recommended, (courses 2 or 5) b/c of 2 having a higher pre-req-for count, it is chosen

        $this->user->fresh();

        $data = ["semester" => 1, 'number_of_courses' => 1];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertSuccessful();
        $response->assertSee("Test Course Two");
        $response->assertDontSee("We could not find enough available courses to meet
            the number of courses you requested due to limitation of course offerings and prerequisites.");

    }


    /**  @test */
    public function correct_courses_are_recommended_4()
    {
        // completed course one
        $this->user->completedCourses()->sync([$this->getCourseId(1)]);

        // three courses can be recommended, (courses 2, 3 or 5) but the
        // requests only asks for two b/c of 2 having a higher pre-req-for count, it is chosen
        // and then b/c course three is a concurrent for course two it is also recommended over 5

        $this->user->fresh();

        $data = ["semester" => 1, 'number_of_courses' => 2];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertSuccessful();
        $response->assertSee("Test Course Two");
        $response->assertDontSee("We could not find enough available courses to meet
            the number of courses you requested due to limitation of course offerings and prerequisites.");
        $response->assertSee("Test 432 can only be taken as a concurrent if you also take Test 311!");

    }

    /**  @test */
    public function correct_courses_are_recommended_5()
    {
        // completed course one
        $this->user->completedCourses()->sync([$this->getCourseId(1), $this->getCourseId(2),
            $this->getCourseId(3)]);

        // two courses to recommend (course 4 and course 5) b/c of semester and pre-req limits and b/c course 5 offers
        // a higher pre-req for count (they are also both semester specific so that's a tie) it is recommended

        $this->user->fresh();

        $data = ["semester" => 1, 'number_of_courses' => 1];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertSuccessful();
        $response->assertSee("Test Course Five");
        $response->assertDontSee("We could not find enough available courses to meet
            the number of courses you requested due to limitation of course offerings and prerequisites.");

    }

    /**  @test */
    public function correct_courses_are_recommended_6()
    {
        // completed course one
        $this->user->completedCourses()->sync([$this->getCourseId(1), $this->getCourseId(2),
            $this->getCourseId(3)]);

        // two courses to recommend (course 4 and course 5) b/c of semester and pre-req limits and b/c course 5 offers
        // a higher pre-req for count (they are also both semester specific so that's a tie) it is recommended

        $this->user->fresh();

        $data = ["semester" => 1, 'number_of_courses' => 1];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertSuccessful();
        $response->assertSee("Test Course Five");
        $response->assertDontSee("We could not find enough available courses to meet
            the number of courses you requested due to limitation of course offerings and prerequisites.");

    }

    /**  @test */
    public function correct_courses_are_recommended_7()
    {
        // completed course one
        $this->user->completedCourses()->sync([$this->getCourseId(1), $this->getCourseId(2),
            $this->getCourseId(5)]);

        // b/c of semester chosen course three will be recommended, in a diff semester course 6 would be recommended

        $this->user->fresh();

        $data = ["semester" => 1, 'number_of_courses' => 1];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertSuccessful();
        $response->assertSee("Test Course Three");
        $response->assertDontSee("Sorry, we couldn't find any courses that fit your criteria...");

    }

    /**  @test */
    public function correct_courses_are_recommended_8()
    {
        // completed course one
        $this->user->completedCourses()->sync([$this->getCourseId(1), $this->getCourseId(2),
            $this->getCourseId(5)]);

        // only course 6 can be recommended so it is

        $this->user->fresh();

        $data = ["semester" => 2, 'number_of_courses' => 1];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertSuccessful();
        $response->assertSee("Test Course Six");
        $response->assertDontSee("Sorry, we couldn't find any courses that fit your criteria...");

    }

    /**  @test */
    public function correct_courses_are_recommended_9()
    {
        // completed course one
        $this->user->completedCourses()->sync([$this->getCourseId(1)]);

        // no courses to recommend b/c of semester and pre-req limits

        $this->user->fresh();

        $data = ["semester" => 3, 'number_of_courses' => 1];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertSuccessful();
        $response->assertDontSee("Test Course Two");
        $response->assertSee("We could not find enough available courses to meet
            the number of courses you requested due to limitation of course offerings and prerequisites.");
        $response->assertSee("Sorry, we couldn't find any courses that fit your criteria...", false);

    }

    private function getCourseId($logicalCourseId)
    {
        return match ($logicalCourseId) {
            1 => Course::where("title", "Test Course One")->first()->id,
            2 => Course::where("title", "Test Course Two")->first()->id,
            3 => Course::where("title", "Test Course Three")->first()->id,
            4 => Course::where("title", "Test Course Four")->first()->id,
            5 => Course::where("title", "Test Course Five")->first()->id,
            default => Course::where("title", "Test Course Six")->first()->id,
        };
    }
}
