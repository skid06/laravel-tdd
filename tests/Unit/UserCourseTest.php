<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\SchoolYear;
use App\Models\CourseUser;
use App\Http\Services\UserEnrollmentService;
use App\Http\Services\CourseService;

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

        $enrollmentService = new UserEnrollmentService($user);
        $enrollmentService->addCourse(rand($courses->first()->id, $courses->count()), 'active');

        $this->assertCount(1, User::all());
        $this->assertCount(5, Course::all());
        $this->assertCount(100, Subject::all());
    }
    
    public function test_a_student_has_active_course()
    {
        $deans = User::factory(5)->create(['role' => '3']);
        $professors = User::factory(5)->create(['role' => '5']);
        $students = User::factory()->count(5)->create(['role' => '6']);
        $courses = Course::factory()->count(5)->create();
        $subjects = Subject::factory()->count(50)->create();            
        $subjects2 = Subject::factory()->count(50)->create();
        $subjects3 = Subject::factory()->count(50)->create();
        $subjects4 = Subject::factory()->count(50)->create();
        $subjects5 = Subject::factory()->count(50)->create();
        $semester1 = Semester::create([
            'name' => '1st',
            'description' => 'First Semester'
        ]);
        $school_year = SchoolYear::create([
            'name' => '2021/2022',
            'description' => 'First School Year'
        ]);

        $courseService = new CourseService($deans[1], $courses[3], $students[3]);
        
        foreach ($subjects as $subject) {
            $courseService->addSubjectToCurriculum($subject);
        }
        $courseService->addCourse($courses[3], 'active');
        
        $courseService2 = new CourseService($deans[2], $courses[1]);
        foreach ($subjects2 as $subject) {
            $courseService2->addSubjectToCurriculum($subject);
        }
        // echo "Active Course from Course Service: " . $courseService2->activeCourse();
        $courseService2->openSubjectForEnrollment($subjects2[3]->id, $professors[1]->id, $semester1->id, $school_year->id, true);
        // $this->assertEquals("This subject has been opened strictly to course for enrollment", $courseService2->openSubjectForEnrollment($subjects2[3]->id, $semester1->id, $school_year->id, true));
        
        // dd($courses[3]->curriculas);
        $this->assertCount(0, $courses[4]->curriculas->all());
        $this->assertCount(50, $courses[3]->curriculas->all());
        $enrollmentService = new UserEnrollmentService($students[4]);
        $this->assertCount(250, Subject::all());
        $enrollmentService->addCourse($courses[4], 'completed');  
        $enrollmentService->addCourse($courses[2], 'active');  
        $this->assertEquals('active', $enrollmentService->activeCourse()->pivot->status);
        // echo "Active Course from Enrollment Service: " .$enrollmentService->activeCourse();
    }
}
