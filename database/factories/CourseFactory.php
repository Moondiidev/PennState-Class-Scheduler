<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "title" => $this->faker->text(100),
            "description" => $this->faker->sentence(20, true),
            "credits" => $this->faker->randomElement([1, 2, 3]),
            "type" => $this->faker->text(8),
            "abbreviation" => $this->faker->text(5),
//            "prerequisites" => $this->faker->optional(0.3)->randomElements(Course::getCourseIDs()),
//            "concurrents" => $this->faker->optional(0.1)->randomElements(Course::getCourseIDs()),
        ];
    }

}
