<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\CourseUser;
use App\Models\Curricula;

class CurriculumTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_curriculum_can_be_added()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create();
        $courses = Course::factory()->count(5)->create();

        $subjects = Subject::factory()->count(50)->create(); 
        $subjects2 = Subject::factory()->count(50)->create(); 
        
        // $courses[3]->addSubject($subjects[0], User::first());
        foreach ($subjects as $subject) {
            $courses[3]->addSubject($subject, User::first());
        }

        $this->assertCount(50, $courses[3]->curriculas->all());
        $this->assertCount(100, Subject::all());
    }
    
    
    // public function test_a_student_can_only_enroll_to_course_curriculas()
    // {
    
    // }
}
