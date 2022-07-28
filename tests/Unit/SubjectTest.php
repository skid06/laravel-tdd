<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\CourseUser;
use App\Models\Curricula;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_subject_to_be_opened_for_enrollment()
    {
        $users = User::factory()->count(5)->create();
        $courses = Course::factory()->count(5)->create();
        $subjects = Subject::factory()->count(50)->create();            
        $subjects2 = Subject::factory()->count(50)->create();
        $subjects3 = Subject::factory()->count(50)->create();
        $subjects4 = Subject::factory()->count(50)->create();
        $subjects5 = Subject::factory()->count(50)->create();

        foreach ($subjects as $subject) {
            $courses[3]->addSubject($subject, User::find(3));
        }
        
        foreach ($subjects2 as $subject) {
            $courses[1]->addSubject($subject, User::find(2));
        }
        
        // echo($courses[1]->curriculas);
        // $this->assertTrue(true);
    }
}
