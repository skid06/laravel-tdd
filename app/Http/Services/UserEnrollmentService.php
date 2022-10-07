<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Enrollment;

class UserEnrollmentService
{
    protected $user;
    /**
     * @user param
     * is optional as child class will not always need this param
     */
    public function __construct(User $user = null){
        $this->user = $user;
    }

    public function addCourse($course, $status)
    {
        if(!$this->user) {
            $message = "No user was given";
            abort(403, $message);
        }

        $allowed_roles = [4,6]; // student and registrar
        if(!in_array($this->user->role, $allowed_roles)) {
            abort(403, "The user is not allowed to enroll for the students.");
        }

        $allowed_roles = ["active", "completed", "incomplete" , "pending"];
        if(!in_array($status, $allowed_roles)) {
            abort(403, "The status is not allowed.");
         }

        $data = $this->user->courses()->attach($course, ['status' => $status]);
        $message = "You have successfully chosen a course.";

        return response()->json([
            'data' => $data,
            'message' => $message
        ], 201); 
    }
    
    public function activeCourse()
    {
        if(!$this->user) {
            return "No user was given";
        }

        if(!$this->user->courses()->exists()) {
            return $this->user->courses; // empty courses []
        }

        return $this->user->courses
                ->first(fn($course) => $course->pivot->status === "active");
    }
    
    public function checkIfSubjectIsInCurriculum(int $subject_id)
    {
        // use $activeCourseSubjectIds if users can have many active courses
        //$activeCourseSubjectIds = $this->user->activeCourse()->map(fn($course) => $course->curriculas->pluck('subject_id')->toArray());
        
        return in_array($subject_id, $this->activeCourse()->curriculas->pluck('subject_id')->toArray());
    }
    
    public function enrollSubject(Enrollment $enrollment, $status)
    {
        if(!$this->user) {
            $message = "No user was given";
            abort(403, $message);
        }

        // check if the user is allowed to enroll for the students
        $allowed_roles = [3,6]; // student and dean
        if(!in_array($this->user->role, $allowed_roles)) {
            abort(403, "The user is not allowed to enroll for the students.");
        }
        
        if(gettype($status) != 'string') {
            abort(403, "Status has to be a string.");
        }

        if(!$this->checkIfSubjectIsInCurriculum($enrollment->subject_id)) {
            abort(403, "This subject is not in your course curriculum");
        }

        if(!$enrollment->course_id) {
            // Enroll subject
            $data = $this->user->enrollments()->attach($enrollment, ['status' => $status]);  
            $message = "You have successfully enrolled this subject. Note: This subject is opened to all courses.";
            
            return response()->json([
                'data' => $data,
                'message' => $message
            ], 201);
        }

        if($this->activeCourse()->id != $enrollment->course_id) {
            abort(403, "This subject is strictly available for ".$enrollment->course->name);
        }

        // Enroll subject
        $data = $this->user->enrollments()->attach($enrollment, ['status' => $status]);
        $message = 'You have successfully enrolled this subject.';   
        
        return response()->json([
            'data' => $data,
            'message' => $message
        ], 201);        
    }
    
}