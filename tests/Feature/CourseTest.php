<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Database\Seeders\CourseSeeder;
use Database\Seeders\SemesterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
    public function auth_user_can_visit_index()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.index'));

        $response->assertSuccessful();
    }

    /**  @test */
    public function auth_user_sees_correct_courses_on_index()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.index'));

        $response->assertSee(['Test Course One', 'Test Course Two', 'Test Course Three']);
    }

    /**  @test */
    public function guest_can_not_visit_index()
    {
        $response = $this->get(route('courses.index'));

        $response->assertRedirect(route('login'));
    }

    /**  @test */
    public function dev_auth_user_can_visit_edit()
    {
        $response = $this->actingAs($this->devUser)->get(route('courses.edit', Course::all()->first()->id));

        $response->assertSuccessful();
    }

    /**  @test */
    public function non_dev_auth_user_can_not_visit_edit()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.edit', Course::all()->first()->id));

        $response->assertForbidden();
    }




}