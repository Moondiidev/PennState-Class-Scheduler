<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Database\Seeders\CourseSeeder;
use Database\Seeders\SemesterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function GuzzleHttp\Psr7\str;

class CourseTest extends TestCase
{

    use RefreshDatabase;

    private $devUser;
    private $regularUser;
    private $courseOne;
    private $courseTwo;
    private $courseThree;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SemesterSeeder::class);
        $this->devUser = User::factory()->create(['name' => 'Ava Dev', 'email' => 'ava@psu.edu']);
        $this->regularUser = User::factory()->create(['name' => 'Eli User', 'email' => 'eli@psu.edu']);
        $this->courseOne = Course::factory()->create(
            ['title' => 'Test Course One', 'abbreviation' => 'Test 221', 'type' => 'Test']);
        $this->courseOne->semesters()->attach([1, 2]);
        $this->courseTwo = Course::factory()->create(
            ['title' => 'Test Course Two', 'abbreviation' => 'Test 311', 'type' => 'Test']);
        $this->courseTwo->semesters()->attach([2]);
        $this->courseThree = Course::factory()->create(
            ['title' => 'Test Course Three', 'abbreviation' => 'Test 432', 'type' => 'Test']);
        $this->courseTwo->semesters()->attach([2, 3]);
    }

    /**  @test */
    public function auth_user_can_visit_courses_index()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.index'));

        $response->assertSuccessful();
    }

    /**  @test */
    public function auth_user_sees_correct_courses_on_courses_index()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.index'));

        $response->assertSee(['Test Course One', 'Test Course Two', 'Test Course Three']);
    }

    /**  @test */
    public function guest_can_not_visit_courses_index()
    {
        $response = $this->get(route('courses.index'));

        $response->assertRedirect(route('login'));
    }

    /**  @test */
    public function dev_auth_user_can_visit_create_course()
    {
        $response = $this->actingAs($this->devUser)->get(route('courses.create', Course::all()->first()->id));

        $response->assertSuccessful();
    }

    /**  @test */
    public function non_dev_auth_user_can_not_visit_create_course()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.create', Course::all()->first()->id));

        $response->assertForbidden();
    }

    /**  @test */
    public function guest_can_not_visit_create_course()
    {
        $response = $this->get(route('courses.create', Course::all()->first()->id));

        $response->assertRedirect(route('login'));
    }

    /**  @test */
    public function dev_auth_user_can_visit_edit_course()
    {
        $response = $this->actingAs($this->devUser)->get(route('courses.edit', Course::all()->first()->id));

        $response->assertSuccessful();
    }

    /**  @test */
    public function non_dev_auth_user_can_not_visit_edit_course()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.edit', Course::all()->first()->id));

        $response->assertForbidden();
    }

    /**  @test */
    public function guest_can_not_visit_edit_course()
    {
        $response = $this->get(route('courses.edit', Course::all()->first()->id));

        $response->assertRedirect(route('login'));
    }

    /**  @test */
    public function dev_auth_user_can_create_a_course()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [1,2]
        ];

        $response = $this->actingAs($this->devUser)->post(route('courses.store', $data));

        $newCourse = Course::where('abbreviation', 'Test 442')->first();

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2,]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => 1]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => 2]);
    }

    /**  @test */
    public function dev_auth_user_can_create_a_course_with_prerequisites()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [1,2], "prerequisites" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->post(route('courses.store', $data));

        $newCourse = Course::where('abbreviation', 'Test 442')->first();

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2,
            "prerequisites" => json_encode([(string) $this->courseOne->id])]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => 1]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => 2]);
    }

    /**  @test */
    public function dev_auth_user_can_create_a_course_with_concurrents()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [1,2], "concurrents" => [$this->courseOne->id],
            "prerequisites" => [$this->courseTwo->id, $this->courseThree->id]
        ];

        $response = $this->actingAs($this->devUser)->post(route('courses.store', $data));

        $newCourse = Course::where('abbreviation', 'Test 442')->first();

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2,
                                             "concurrents" => json_encode([(string) $this->courseOne->id]),
         "prerequisites" => json_encode([(string) $this->courseTwo->id, (string) $this->courseThree->id])
        ]);

        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => 1]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => 2]);
    }

    /**  @test */
    public function dev_auth_user_can_create_a_course_with_prerequisites_and_concurrents()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [1,2], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->post(route('courses.store', $data));

        $newCourse = Course::where('abbreviation', 'Test 442')->first();

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2,
                                             "concurrents" => json_encode([(string) $this->courseOne->id])]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => 1]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => 2]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_a_course_title_already_taken()
    {

        $data = [
            "title" => $this->courseOne->title, "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [1,2], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                                                   ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('title');
        $this->assertDatabaseMissing('courses', ["title" => $this->courseOne->title, "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2,
                                             "concurrents" => json_encode([(string) $this->courseOne->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_a_course_abbreviation_already_taken()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => $this->courseOne->abbreviation, "description" => "Some random description",
            "credits" => 2, "semester" => [1,2], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('abbreviation');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => $this->courseOne->abbreviation,
                                                 "description" => "Some random description", "credits" => 2,
                                                 "concurrents" => json_encode([(string) $this->courseOne->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_no_description()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "",
            "credits" => 2, "semester" => [1,2], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('description');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "", "credits" => 2,
                                                 "concurrents" => json_encode([(string) $this->courseOne->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_invalid_number_of_credits()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [1,2], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('credits');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 0,
                                                 "concurrents" => json_encode([(string) $this->courseOne->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_invalid_semester_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [4], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('semester');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,
                                                 "concurrents" => json_encode([(string) $this->courseOne->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_invalid_concurrents_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [4], "concurrents" => [100]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('concurrents');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,
                                                 "concurrents" => json_encode([(string) 100])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_invalid_prerequisites_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [4], "prerequisites" => [100]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('prerequisites');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,
                                                 "prerequisites" => json_encode([(string) 100])]);
    }


}
