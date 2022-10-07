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
        $response->assertSee('Math 1');
    }

    public function test_a_professor_can_add_a_subject()
    {
        $this->withoutExceptionHandling();
        $subjects = Subject::factory(10)->create();
        $users = User::factory(10)->create(['role' => '5']);
        // $users[0]->professor_subjects()->attach($subjects[1]);

        // echo "USERS: ".$users[0]->load('professor_subjects');

        $response = $this->actingAs($users[1])
            ->post('/api/subjects/professor/add/'.$subjects[1]->id);

        $response->assertSee("A professor has added a subject.");
        $response->assertStatus(201);

        echo "USERS: ".$users[0]->load('professor_subjects');
    }
}
