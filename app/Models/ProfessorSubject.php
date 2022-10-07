<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessorSubject extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function users()
    // {
    //     return $this->belongsToMany(User::class , 'professor_subject', 'id', 'professor_id');
    // }

    // public function subjects()
    // {
    //     return $this->hasMany(Subject::class);
    //     // return $this->belongsToMany(Subject::class , 'professor_subject', 'id', 'subject_id');
    // }    
}
