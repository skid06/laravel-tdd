<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class , 'course_user', 'user_id', 'course_id')->withPivot('status');
    }

    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class , 'enrollment_user', 'user_id', 'enrollment_id')->withPivot('status');
    }

    public function addEnrollment($enrollment, $status)
    {
        $this->enrollments()->attach($enrollment, ['status' => $status]);
    }

    public function addCourse($course, $status)
    {
        $this->courses()->attach($course, ['status' => $status]);
    }
    
    public function activeCourse()
    {
        // dd(gettype($this->courses));
        if(!$this->courses()->exists()) {
            return $this->courses;
        }

        return $this
                ->courses
                ->filter(fn($course) => $course->pivot->status === "active");
                //->map(fn($course) => $course);
    }
    
    public function checkIfSubjectIsInCurriculum(int $subject_id)
    {
        if(!in_array($subject_id, $this->activeCourse()[0]->curriculas->pluck('subject_id')->toArray())) {
            return false;
        }
        return true;
    }
    
    public function enrollSubject(int $subject_id, int $enrollment, string $status)
    {
        if(!$this->checkIfSubjectIsInCurriculum($subject_id)) {
            return('This subject is not in your course curriculum');
        }
        // Enroll subject
        $this->addEnrollment($enrollment, $status);
        return 'This subject is in your course';
    }
    
}
