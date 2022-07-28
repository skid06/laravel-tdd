<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
    
    public function course()
    {
        return $this->hasMany(Course::class);
    }
    
    public function semester()
    {
        return $this->hasMany(Semester::class);
    }
}
