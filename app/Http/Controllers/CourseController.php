<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Http\Services\CourseService;
use App\Http\Services\UserEnrollmentService;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseRequest $request)
    {
        $data = $request->validated();
        $course = Course::create($data);

        return response()->json([
            'data' => $course
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
    }

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
            request()->subject_id, 
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
