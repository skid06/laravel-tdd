<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\SchoolYear;
use App\Models\Enrollment;
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
        
        $semester2 = Semester::create([
            'name' => '2nd',
            'description' => 'Second Semester'
        ]);
        
        $semester3 = Semester::create([
            'name' => '3rd',
            'description' => 'Third Semester'
        ]);
        
        $semester1 = Semester::create([
            'name' => '1st',
            'description' => 'First Semester'
        ]);
        
        $school_year = SchoolYear::create([
            'name' => '2021/2022',
            'description' => 'First School Year'
        ]);
        // $subjects[0]->enrollments($)
        $enrollment1 = Enrollment::create([
            'subject_id' => $subjects[0]->id,
            'semester_id' => $semester1->id,
            'school_year_id' => $school_year->id,
            'strict_to_course' => 0,
            'status' => 'open'
        ]);
        
        $enrollment2 = Enrollment::create([
            'subject_id' => $subjects[22]->id,
            'semester_id' => $semester1->id,
            'school_year_id' => $school_year->id,
            'strict_to_course' => 0,
            'status' => 'open'
        ]);
        
        $users[1]->addCourse($courses[1], 'active');  
        $users[0]->addCourse($courses[2], 'inactive');  
        $this->assertEquals('active', $users[1]->activeCourse()[0]->pivot->status);
        $this->assertCount(2, Enrollment::all());
        echo($users[1]->activeCourse()[0]->curriculas->pluck('subject_id'));
        $subj = Subject::where('id', 1)->first();
        echo $users[1]->enrollSubject($subj->id);
        echo $subj->id;
        // $this->assertTrue(true);
    }
}
