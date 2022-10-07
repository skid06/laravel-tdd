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

    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const DEAN = 3;
    const REGISTRAR = 4;
    const PROFESSOR = 5;
    const STUDENT = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    // protected $with = ['professor_subjects'];

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
        // return $this->belongsToMany(Subject::class , 'professor_subject', 'subject_id', 'professor_id');
    }

    public function professor_subjects()
    {
        return $this->belongsToMany(Subject::class , 'professor_subject', 'user_id', 'subject_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class , 'course_user', 'user_id', 'course_id')->withPivot('status');
    }

    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class , 'enrollment_user', 'user_id', 'enrollment_id')->withPivot('status');
    }
    
}
