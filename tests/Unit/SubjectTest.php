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
use App\Http\Services\UserEnrollmentService;
use App\Http\Services\CourseService;
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
        $subjects = Subject::factory()->count(50)->create();            
        $subjects2 = Subject::factory()->count(50)->create();
        $subjects3 = Subject::factory()->count(50)->create();
        $subjects4 = Subject::factory()->count(50)->create();
        $subjects5 = Subject::factory()->count(50)->create();
        
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
            'max_students' => 50,
            'status' => 'open'
        ]);
        
        $enrollment2 = Enrollment::create([
            'subject_id' => $subjects[22]->id,
            'semester_id' => $semester1->id,
            'school_year_id' => $school_year->id,
            'strict_to_course' => 0,
            'max_students' => 50,
            'status' => 'open'
        ]);
        
        $this->assertCount(2, Enrollment::all());
    }

    public function test_a_user_can_enroll_subject_in_course_curriculum()
    {
        $users = User::factory()->count(5)->create();
        $courses = Course::factory()->count(5)->create();
        $subjects = Subject::factory()->count(50)->create();            
        $subjects2 = Subject::factory()->count(50)->create();
        $subjects3 = Subject::factory()->count(50)->create();
        $subjects4 = Subject::factory()->count(50)->create();
        $subjects5 = Subject::factory()->count(50)->create();
        $deans = User::factory()->count(5)->create(['role' => '3']);

        $courseService1 = new CourseService($deans[1], $courses[3]);
        foreach ($subjects as $subject) {
            $courseService1->addSubjectToCurriculum($subject);
        }

        $courseService2 = new CourseService($deans[3], $courses[1]);
        foreach ($subjects2 as $subject) {
            $courseService2->addSubjectToCurriculum($subject);
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
            'max_students' => 50,
            'status' => 'open'
        ]);
        
        $enrollment2 = Enrollment::create([
            'subject_id' => $subjects[22]->id,
            'semester_id' => $semester1->id,
            'school_year_id' => $school_year->id,
            'strict_to_course' => 0,
            'max_students' => 50,
            'status' => 'open'
        ]);

        $enrollment3 = Enrollment::create([
            'subject_id' => $subjects[23]->id,
            'semester_id' => $semester1->id,
            'school_year_id' => $school_year->id,
            'strict_to_course' => 0,
            'max_students' => 50,
            'status' => 'open'
        ]);
        $enrollmentService = new UserEnrollmentService($users[0]);
        $enrollmentService->addCourse($courses[1], 'active');
        $enrollmentService->addCourse($courses[2], 'pending');
        // $userHasCourse = $users[0]->activeCourse() ? $users[0]->activeCourse()[0]->pivot->status : $users[1]->courses;
        $this->assertEquals('active', $enrollmentService->activeCourse()->pivot->status);
        $this->assertCount(3, Enrollment::all());
        $this->assertTrue($enrollmentService->checkIfSubjectIsInCurriculum(100));

        // echo($users[0]->activeCourse()[0]->curriculas->pluck('subject_id'));
        $subj = Subject::where('id', 1)->first();
        // echo $userHasCourse;
    }

    public function test_a_user_can_enroll_subject_in_curriculum_strict_to_course()
    {
        $users = User::factory()->count(5)->create();
        $courses = Course::factory()->count(5)->create();
        $subjects = Subject::factory()->count(50)->create();            
        $subjects2 = Subject::factory()->count(50)->create();
        $subjects3 = Subject::factory()->count(50)->create();
        $deans = User::factory()->count(5)->create(['role' => '3']);

        $courseService1 = new CourseService($deans[2], $courses[1]);
        foreach ($subjects as $subject) {
            $courseService1->addSubjectToCurriculum($subject);
        }
        
        $courseService2 = new CourseService($deans[1], $courses[2]);
        foreach ($subjects as $subject) {
            $courseService2->addSubjectToCurriculum($subject);
        }

        $courseService3 = new CourseService($deans[4], $courses[3]);
        foreach ($subjects3 as $subject) {
            $courseService3->addSubjectToCurriculum($subject);
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
        
        $enrollment1 = Enrollment::create([
            'subject_id' => $subjects2[40]->id,
            'semester_id' => $semester1->id,
            'school_year_id' => $school_year->id,
            'strict_to_course' => 0,
            'max_students' => 50,
            'status' => 'open'
        ]);
        
        $enrollment2 = Enrollment::create([
            'subject_id' => $subjects[22]->id,
            'semester_id' => $semester1->id,
            'school_year_id' => $school_year->id,
            'strict_to_course' => 1,
            'course_id' => $courses[2]->id,
            'max_students' => 50,
            'status' => 'open'
        ]);

        $enrollment3 = Enrollment::create([
            'subject_id' => $subjects[43]->id,
            'semester_id' => $semester1->id,
            'school_year_id' => $school_year->id,
            'strict_to_course' => 0,
            'max_students' => 50,
            'status' => 'open'
        ]);

        $enrollment4 = Enrollment::create([
            'subject_id' => $subjects[40]->id,
            'semester_id' => $semester1->id,
            'school_year_id' => $school_year->id,
            'course_id' => $courses[2]->id,
            'max_students' => 50,
            'status' => 'open'
        ]);

        $enrollmentService = new UserEnrollmentService($users[1]);
        $enrollmentService->addCourse($courses[2], 'active');

        $enrollmentService0 = new UserEnrollmentService($users[0]);
        $enrollmentService0->addCourse($courses[1], 'active');
        
        $this->assertTrue($enrollmentService->checkIfSubjectIsInCurriculum($subjects[22]->id));
        // echo($users[1]->activeCourse()[0]->curriculas->pluck('subject_id'));
        
        // $this->assertEquals("This subject is in your course", $enrollmentService->enrollSubject($enrollment2, 'active'));
        $enrollmentService->enrollSubject($enrollment2, 'pending');
        // $this->assertEquals("This subject is in your curriculum but not strictly in your course", $enrollmentService->enrollSubject($enrollment3, 'active'));
        $enrollmentService->enrollSubject($enrollment3, 'pending');
        // $this->assertEquals("This subject is in your course", $enrollmentService->enrollSubject($enrollment4, 'active'));
        $enrollmentService->enrollSubject($enrollment4, 'pending');
        // echo("Fdsaf ". $users[1]->enrollments);
    }

    public function test_a_user_can_open_enrollment_for_subjects_strictly_to_curriculum()
    {
        $users = User::factory()->count(5)->create();
        $courses = Course::factory()->count(5)->create();
        $subjects = Subject::factory()->count(50)->create();            
        $subjects2 = Subject::factory()->count(50)->create();
        $subjects3 = Subject::factory()->count(50)->create();
        $deans = User::factory()->count(4)->create(['role' => '3']);

        $courseService1 = new CourseService($deans[1], $courses[1]);
        foreach ($subjects as $subject) {
            $courseService1->addSubjectToCurriculum($subject);
        }
        
        $courseService2 = new CourseService($deans[1], $courses[2]);
        foreach ($subjects2 as $subject) {
            $courseService2->addSubjectToCurriculum($subject);
        }

        $courseService3 = new CourseService($deans[1], $courses[2], $users[1]);
        foreach ($subjects3 as $subject) {
            $courseService3->addSubjectToCurriculum($subject);
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

        $enrollmentService = new UserEnrollmentService($users[1]);
        $enrollmentService->addCourse($courses[2], 'active');

        $subject = $subjects2[40]->id;
        $canOpenSubjectForEnrollment = $enrollmentService->checkIfSubjectIsInCurriculum($subject);
        $this->assertTrue($canOpenSubjectForEnrollment);

        if($canOpenSubjectForEnrollment){
            $enrollment1 = Enrollment::create([
                'subject_id' => $subject,
                'semester_id' => $semester1->id,
                'school_year_id' => $school_year->id,
                'strict_to_course' => 0,
                'max_students' => 50,
                'status' => 'open'
            ]);
        }
        
        $this->assertCount(1, Enrollment::all());
        // $this->assertEquals("This subject is in your curriculum but not strictly in your course", $courseService3->enrollSubject($enrollment1, 'active'));
        $courseService3->enrollSubject($enrollment1, 'active');
    }
}
