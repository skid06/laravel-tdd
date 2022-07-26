<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use Laravel\Sanctum\Sanctum;

class CourseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_a_course_name_is_required()
    {
        $response = $this->actingAs($user = User::factory()->make())
            ->post('/api/courses', [
                'name' => '',
                'description' => 'Test',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_a_course_description_is_required()
    {
        $response = $this->actingAs($user = User::factory()->make())
            ->post('/api/courses', [
                'name' => 'Test',
                'description' => '',
            ]);

        $response->assertSessionHasErrors('description');
    }
    
    public function test_a_course_can_be_added()
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($user = User::factory()->make())
            ->post('/api/courses', [
                'name' => 'Cool Book Title',
                'description' => 'Test',
            ]);

        $response->assertStatus(201);
    }

    public function test_a_course_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $this->post('/api/courses', [
            'name' => 'Cool Book Title',
            'description' => 'Test',
        ]);

        $course = Course::first();
        $this->assertCount(1, Course::all());

        $this->delete('/api/courses/'. $course->id);

        $this->assertCount(0, Course::all());
    }
}
