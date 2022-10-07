<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\EnrollmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('courses', CourseController::class);
    Route::resource('semesters', SemesterController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('enrollments', EnrollmentController::class);
    Route::post('courses/curriculum/subject/add', [CourseController::class, 'addSubjectToCurriculum']);
    Route::post('courses/open/subject/{subject_id}', [CourseController::class, 'openSubjectForEnrollment']);
    Route::post('courses/user/add/{course_id}', [CourseController::class, 'userAddCourse']);
    Route::post('subjects/professor/add/{subject}', [SubjectController::class, 'addSubject']);
});