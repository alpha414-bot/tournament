<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class GradeLevel extends Model
{
    use HasFactory;

    public function classLists(){
        return $this->hasMany(ClassList::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public static function gradeLevel($level)
    {
        return self::where('grade_level', $level)->first();
    }
}
