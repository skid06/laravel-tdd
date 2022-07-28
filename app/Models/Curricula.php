<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curricula extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
