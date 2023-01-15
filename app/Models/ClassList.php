<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassList extends Model
{
    use HasFactory;

    protected $table='classes';

    protected $guarded=[];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function section(){
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function gradeLevel(){
        return $this->belongsTo(GradeLevel::class, 'grade_level_id');
    }

    public function schoolYear(){
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function teacher(){
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function students(){
        return $this->hasMany(StudentSubject::class);
    }

}
