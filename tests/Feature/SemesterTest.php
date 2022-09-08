<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Semester;
use Laravel\Sanctum\Sanctum;

class SemesterTest extends TestCase
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
            ->post('/api/semesters', [
                'name' => '',
                'description' => 'Test',
            ]);

        $response->assertSessionHasErrors('name');
        // $response->assertStatus(201);
    }

    public function test_a_course_description_is_required()
    {
        $response = $this->actingAs($user = User::factory()->make())
            ->post('/api/semesters', [
                'name' => 'Test',
                'description' => '',
            ]);

        $response->assertSessionHasErrors('description');
    }
    public function test_a_semester_can_be_added()
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($user = User::factory()->make())
            ->post('/api/semesters', [
                'name' => 'First Semester',
                'description' => 'First Semester for the School Year',
            ]);

        $response->assertStatus(201);
    }
}
