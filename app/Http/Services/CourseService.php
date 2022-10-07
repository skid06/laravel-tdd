<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Http\Services\UserEnrollmentService;

class CourseService extends UserEnrollmentService
{
    private $course;
    private $staff;

    public function __construct(User $staff, Course $course, User $user = null){
        parent::__construct($user);
        $this->course = $course;
        $this->staff = $staff;
    }  
        
    public function addSubjectToCurriculum(Subject $subject)
    {
        $allowed_roles = [3,4]; // registrar and dean
        if(!in_array($this->staff->role, $allowed_roles)) {
            $message = "Only the dean and registrar can open the subject for enrollment.";
            abort(403, $message);
        }

        $data = $this->course->curriculas()->create([
            'subject_id' => $subject->id,
            'user_id' => $this->staff->id
        ]);
        $message = "You have added a new subject to the Course Curriculum.";

        return response()->json([
            'data' => $data,
            'message' => $message
        ], 201);
    }

    // @override
    public function activeCourse()
    {
        return $this->course;
    }

    public function openSubjectForEnrollment($subject_id, $professor_id, $semester_id, $school_year_id, $strict = true)
    {
        $allowed_roles = [3,4]; // registrar and dean
        if(!in_array($this->staff->role, $allowed_roles)) {
            $message = "Only the dean and registrar can open the subject for enrollment.";
            abort(403, $message);
        }

        if(gettype($strict) != 'boolean') {
            $message = '$strict parameter must be a boolean';
            abort(403, $message);
        }

        // TODO: check if professor can teach the subject

        if(!$strict) {        
            $enrollment = Enrollment::create([
                'subject_id' => $subject_id,
                'semester_id' => $semester_id,
                'school_year_id' => $school_year_id,
                'added_by' => $this->staff->role,
                'professor_id' => $professor_id,
                'strict_to_course' => 0,
                'max_students' => 50,
                'status' => 'open'
            ]);            
            $message = 'This subject has been opened for enrollment to all courses students.';

            return response()->json([
                'data' => $enrollment,
                'message' => $message
            ], 201);
        }

        if(!$this->checkIfSubjectIsInCurriculum($subject_id)){
            $message = "Subject is not in curriculum.";
            abort(403, $message);
        }

        $enrollment = Enrollment::create([
            'subject_id' => $subject_id,
            'semester_id' => $semester_id,
            'school_year_id' => $school_year_id,
            'added_by' => $this->staff->role,
            'professor_id' => $professor_id,
            'strict_to_course' => 1,
            'course_id' => $this->course->id,
            'max_students' => 50,
            'status' => 'open'
        ]);
        $message = 'This subject has been opened strictly to course for enrollment';

        return response()->json([
            'data' => $enrollment,
            'message' => $message
        ], 201);
    }
}