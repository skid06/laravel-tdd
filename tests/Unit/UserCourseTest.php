<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\CourseUser;

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
        
        $user = User::factory()->create();
        $courses = Course::factory()
                    ->count(5)
                    ->create();

        $subjects1 = Subject::factory()
                    ->count(50)
                    ->create(); 
                    
        $subjects2 = Subject::factory()
            ->count(50)
            ->create();             

        $user->addCourse(rand($courses->first()->id, $courses->count()), 'active');
        // dd($subjects);
        $this->assertCount(1, User::all());
        $this->assertCount(5, Course::all());
        $this->assertCount(100, Subject::all());
    }
    
    public function test_a_student_has_active_course()
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
        // dd($courses[3]->curriculas);
        // $this->assertCount(1, User::all());
        $this->assertCount(0, $courses[4]->curriculas->all());
        $this->assertCount(50, $courses[3]->curriculas->all());
        $this->assertCount(250, Subject::all());
        $users[0]->addCourse($courses[3], 'active');  
        $users[0]->addCourse($courses[2], 'inactive');  
        $this->assertEquals('active', $users[0]->activeCourse()[0]->pivot->status);
        echo($users[0]->activeCourse());
    }
}
