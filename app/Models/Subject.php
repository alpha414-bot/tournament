<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\CustomBadRequestException;
use App\Services\SubjectService;
use Illuminate\Support\Arr;
use phpDocumentor\Reflection\Types\Self_;

use function PHPUnit\Framework\isNull;

class Subject extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class,'grade_level_id');
    }

    public function classLists(){
        return $this->hasMany(ClassList::class);
    }
}
