<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\SchoolYear;
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
                'status' => 'active'
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_a_course_description_is_required()
    {
        $response = $this->actingAs($user = User::factory()->make())
            ->post('/api/courses', [
                'name' => 'Test',
                'description' => '',
                'status' => 'active'
            ]);

        $response->assertSessionHasErrors('description');
    }
    
    public function test_a_course_can_be_added()
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($user = User::factory()->make())
            ->post('/api/courses', [
                'name' => 'Bachelor in Science of Information Technology',
                'description' => 'Bachelor in Science of Information Technolog - BSIT',
                'code' => 'BSIT',
                'status' => 'active'
            ]);

        $response->assertStatus(201);
        $response->assertSee("Bachelor in Science of Information Technology");
    }

    public function test_a_course_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $this->post('/api/courses', [
            'name' => 'Bachelor in Science of Information Technology',
            'description' => 'Bachelor in Science of Information Technolog - BSIT',
            'code' => 'BSIT',
            'status' => 'active'
        ]);

        $course = Course::first();
        $this->assertCount(1, Course::all());

        $this->delete('/api/courses/'. $course->id);

        $this->assertCount(0, Course::all());
    }

    public function test_subjects_can_be_added_to_course_curriculum()
    {
        $this->withoutExceptionHandling();
        $courses = Course::factory()->count(5)->create();
        $subjects = Subject::factory()->count(50)->create(); 
        $semester1 = Semester::create([
            'name' => '1st',
            'description' => 'First Semester'
        ]);
        
        $school_year = SchoolYear::create([
            'name' => '2021/2022',
            'description' => 'First School Year'
        ]);

        $users = User::factory()->count(4)->create();
        $deans = User::factory()->count(4)->create(['role' => '4']);
        // $user = Sanctum::actingAs(
        //     User::factory()->create(),
        //     ['*']
        // );
        $response = $this->actingAs($deans[1])
            ->post('/api/courses/curriculum/subject/add', [
                'course_id' => $courses[1]->id,
                'subject_id' => $subjects[1]->id,
                'notes' => "Test Description"
            ]);

        $response->assertStatus(201);
    }

    public function test_subjects_can_be_opened_for_enrollment()
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
        $professors = User::factory(5)->create(['role' => '5']);

        $response = $this->actingAs($deans[3])
            ->post('/api/courses/curriculum/subject/add', [
                'course_id' => $courses[1]->id,
                'subject_id' => $subjects[1]->id,
                'notes' => "Test Description"
            ]);

        $response->assertStatus(201);

        
        $response2 = $this->actingAs($deans[1])
            ->post('/api/courses/open/subject/'.$subjects[1]->id, [
                'semester_id' => $semester1->id,
                'course_id' => $courses[1]->id,
                'school_year_id' => $school_year->id,
                'professor_id' => $professors[1]->id,
                'status' => true
            ]);

        $response2->assertSee("This subject has been opened strictly to course for enrollment");
        $response2->assertStatus(201);
    }
}
