<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;
use App\Models\User;
use App\Models\Subject;
// use Illuminate\Database\Eloquent\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curriculum>
 */
class CurriculumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // 'author_id' => factory(Author::class),
        return [
            'course_id' => Course::factory(), //factory(Course::class)->make()->id,
            'subject_id' => Subject::factory(), //factory(Subject::class)->count(40)->create()->id,
            'user_id' => User::factory(), //factory(User::class)->create()->id,
            'notes' => fake()->text()
        ];
    }
}
