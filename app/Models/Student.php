<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = "users";

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'grade_level_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function enrollments(){
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    public function student_subjects(){
        return $this->hasMany(StudentSubject::class);
    }
}
