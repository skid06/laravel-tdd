<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'units'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
    // subjects belongs to many curriculum and vice versa

    public function curriculas()
    {
        return $this->belongsToMany(Curricula::class);
    }
    
    public function enrollments()
    {
        return $this->belongsToMany(Enrollments::class);
    }
}
