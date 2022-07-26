<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
// use App\Models\Course;
// use Laravel\Sanctum\Sanctum;
// use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class UserCourseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_user_can_add_course()
    {
        $this->withoutExceptionHandling();

        // Sanctum::actingAs(
        //     User::factory()->create(),
        //     ['*']
        // );
        // $response = $this->post('/api/enrollments', [
        //     'user_id' => '',
        //     'course_id' => '',
        // ]);
        
        $user = User::factory()->create();
        $this->assertCount(1, User::all());
    }
}
