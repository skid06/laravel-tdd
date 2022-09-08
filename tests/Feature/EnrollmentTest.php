<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\SchoolYear;
use Laravel\Sanctum\Sanctum;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_a_student_can_add_a_course()
    {
        $this->withoutExceptionHandling();
        $courses = Course::factory()->count(5)->create();
        $subjects = Subject::factory()->count(50)->create(); 
        $subjects2 = Subject::factory()->count(50)->create();
        $semester1 = Semester::create([
            'name' => '1st',
            'description' => 'First Semester'
        ]);
        
        $school_year = SchoolYear::create([
            'name' => '2021/2022',
            'description' => 'First School Year'
        ]);

        $users = User::factory()->count(4)->create();
        $deans = User::factory()->count(4)->create(['role' => '3']);

        $response = $this->actingAs($deans[1])
            ->post('/api/courses/curriculum/subject/add', [
                'course_id' => $courses[1]->id,
                'subject_id' => $subjects[1]->id,
                // 'user_id' => $users[1]->id,
                'notes' => "Test Description"
            ]);

        $response->assertStatus(201);

        $deans = User::factory()->count(4)->create(['role' => '3']);

        $response2 = $this->actingAs($deans[1])
            ->post('/api/courses/open/subject/'.$subjects[1]->id, [
                'semester_id' => $semester1->id,
                'course_id' => $courses[1]->id,
                'school_year_id' => $school_year->id,
                'status' => true
            ]);

        $response2->assertSee("This subject has been opened strictly to course for enrollment");
        $response2->assertStatus(201);

        $response3 = $this->actingAs($users[1])
            ->post('/api/courses/user/add/'.$courses[1]->id, [
                'status' => 'active'
            ]);

        $response3->assertSee("You have successfully chosen a course.");
        $response3->assertStatus(201);        
    }

    public function test_a_student_can_enroll_a_subject()
    {
        // $this->withoutExceptionHandling();
        $courses = Course::factory()->count(5)->create();
        $subjects = Subject::factory()->count(50)->create(); 
        $subjects2 = Subject::factory()->count(50)->create();
        $semester1 = Semester::create([
            'name' => '1st',
            'description' => 'First Semester'
        ]);
        
        $school_year = SchoolYear::create([
            'name' => '2021/2022',
            'description' => 'First School Year'
        ]);

        $users = User::factory()->count(4)->create();
        $deans = User::factory()->count(4)->create(['role' => '3']);

        $response = $this->actingAs($deans[1])
            ->post('/api/courses/curriculum/subject/add', [
                'course_id' => $courses[1]->id,
                'subject_id' => $subjects[1]->id,
                // 'user_id' => $users[1]->id,
                'notes' => "Test Description"
            ]);

        $response->assertStatus(201);

        $response2 = $this->actingAs($deans[1])
            ->post('/api/courses/open/subject/'.$subjects[1]->id, [
                'semester_id' => $semester1->id,
                'course_id' => $courses[1]->id,
                'school_year_id' => $school_year->id,
                'status' => true
            ]);

        $response2->assertSee("This subject has been opened strictly to course for enrollment");
        $response2->assertStatus(201);

        $response3 = $this->actingAs($users[1])
            ->post('/api/courses/user/add/'.$courses[1]->id, [
                'status' => 'active'
            ]);

        $response3->assertSee("You have successfully chosen a course.");
        $response3->assertStatus(201); 
        
        $response4 = $this->actingAs($users[1])
            ->post('/api/enrollments', [
                'enrollment_id' => Enrollment::first()->id,
                'status' => 'active'
            ]);

        $response4->assertSee("You have successfully enrolled this subject.");
        $response4->assertStatus(201);         
    }
}
