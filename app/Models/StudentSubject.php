<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class StudentSubject extends Model
{
    use HasFactory;

    protected $table ='students_subjects';

    protected $guarded=[];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class,'enrollment_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_id');
    }

    public function classList()
    {
        return $this->belongsTo(ClassList::class,'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }

}
