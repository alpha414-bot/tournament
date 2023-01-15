<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id');
    }

    public function school_year(){
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function section(){
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function enrolled_subjects(){
        return $this->hasMany(StudentSubject::class);
    }

}
