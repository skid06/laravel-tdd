<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function curriculas()
    {
        return $this->hasMany(Curricula::class);
    }
    
    public function enrollments()
    {
        return $this->belongsToMany(Enrollments::class);
    }
    
    public function addSubject($subject, $user)
    {
        $this->curriculas()->create([
            'subject_id' => $subject->id,
            'user_id' => $user->id
        ]);
    }
}
