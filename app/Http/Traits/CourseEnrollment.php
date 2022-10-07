<?php
namespace App\Http\Traits;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Http\Services\CourseService;
use App\Http\Services\UserEnrollmentService;

trait CourseEnrollment 
{
    public function addSubjectToCurriculum()
    {
        $dean = auth()->user();
        $course = Course::find(request()->course_id);
        $subject = Subject::find(request()->subject_id);
        $courseService = new CourseService($dean, $course);
        
        return $courseService->addSubjectToCurriculum($subject);
    }

    public function openSubjectForEnrollment($subject_id)
    {
        $dean = auth()->user();
        $course = Course::find(request()->course_id);
        $courseService = new CourseService($dean, $course);
        $status = isset(request()->status) ? request()->status : false;

        return $courseService->openSubjectForEnrollment(
            $subject_id,
            request()->professor_id, 
            request()->semester_id, 
            request()->school_year_id, 
            $status
        );
    }

    public function userAddCourse($course_id)
    {
        $user = auth()->user();
        $course = Course::find($course_id);
        $enrollmentService = new UserEnrollmentService($user);

        return $enrollmentService->addCourse($course->id, request()->status);
    }
}