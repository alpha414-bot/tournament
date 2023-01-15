<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getSection($section)
    {
        return self::where('section', $section)->first();
    }

    public function classLists(){
        return $this->hasMany(ClassList::class);
    }

    public function gradeLevel(){
        return $this->belongsTo(GradeLevel::class, 'grade_level_id');
    }

    public function students(){
        return $this->hasMany(Student::class);
    }
}
