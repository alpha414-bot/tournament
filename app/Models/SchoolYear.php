<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $table= 'school_years';
    
    protected $guarded=[];

    public static function schoolYear($year)
    {
        return SchoolYear::where('school_year', $year)->first();
    }

    public function classLists(){
        return $this->hasMany(ClassList::class);
    }
}
