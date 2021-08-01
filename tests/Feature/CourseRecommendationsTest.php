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


        $courseTwoPrerequisites = ["1"];
        $this->courseTwo = Course::factory()->create(
            ['title' => 'Test Course Two', 'abbreviation' => 'Test 311', 'type' => 'Test',
             'semester_specific' => true, 'prerequisites_for_count' => 2,
                'prerequisites' => $courseTwoPrerequisites]);
        $this->courseTwo->semesters()->attach([2]);


        $courseThreePrerequisites = ["2"];
        $courseThreeConcurrents = ["2"];
        $this->courseThree = Course::factory()->create(
            ['title' => 'Test Course Three', 'abbreviation' => 'Test 432', 'type' => 'Test',
             'semester_specific' => false, 'prerequisites_for_count' => 1,
             'prerequisites' => $courseThreePrerequisites, 'concurrents' => $courseThreeConcurrents]);
        $this->courseThree->semesters()->attach([1, 3]);


        $courseFourPrerequisites = ["3"];
        $this->courseFour = Course::factory()->create(
            ['title' => 'Test Course Four', 'abbreviation' => 'Test 512', 'type' => 'Test',
             'semester_specific' => false, 'prerequisites_for_count' => 0,
             'prerequisites' => $courseFourPrerequisites]);
        $this->courseFour->semesters()->attach([1,3]);


        $courseFivePrerequisites = ["1"];
        $this->courseFive = Course::factory()->create(
            ['title' => 'Test Course Five', 'abbreviation' => 'Test 661', 'type' => 'Test',
             'semester_specific' => true, 'prerequisites_for_count' => 1,
             'prerequisites' => $courseFivePrerequisites]);
        $this->courseFive->semesters()->attach([1]);


        $courseSixPrerequisites = ["2", "5"];
        $this->courseSix = Course::factory()->create(
            ['title' => 'Test Course Five', 'abbreviation' => 'Test 654', 'type' => 'Test',
             'semester_specific' => false, 'prerequisites_for_count' => 0,
             'prerequisites' => $courseSixPrerequisites]);
        $this->courseSix->semesters()->attach([2, 3]);
    }

    /**  @test */
    public function correct_courses_are_recommended_1()
    {
        // no completed courses
        // $this->user->completedCourses()->sync([]);

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
        $this->user->completedCourses()->sync([1]);

        $data = ["semester" => 1, 'number_of_courses' => 2];

        $response = $this->actingAs($this->user)->from(route('recommendations'))->post(route('recommendationResults', $data));

        $response->assertSuccessful();
        $response->assertSee("Test Course One");
        $response->assertSee("We could not find enough available courses to meet
            the number of courses you requested due to limitation of course offerings and prerequisites.");

    }
}
