<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Subject;
use Laravel\Sanctum\Sanctum;

class SubjectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_subject_can_be_added()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->post('/api/subjects', [
            'name' => 'Math 1',
            'description' => 'Test',
            'units' => 3
        ]);

        $response->assertStatus(201);
    }
}
