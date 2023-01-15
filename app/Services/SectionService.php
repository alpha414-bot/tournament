<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\Grade;
use App\Exceptions\CustomBadRequestException;
use App\Helpers\ResponseHelper;
use App\Models\ClassList;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class SectionService
{
    protected $model;

    public function __construct(Section $section)
    {
        $this->model = $section;
    }

    public function findAll($request)
    {   
        $model = $this->model->with(['gradeLevel'])->orderBy('grade_level_id');

        if($request->has('search')){
            $model = $model->where('section','like', '%' . preg_quote($request->search) . '%' );
        }

        if($request->filled('id')){
            $model = $model->where('id',$request->id)->first();

            return $model;
        }

        return $model->get();
        
    }

    public function addSection($data)
    {
        if (!$data) {
            throw new CustomBadRequestException('No fields to create');
        }
            
        try
        {   
            $record = Section::where('section', 'like', '%' . preg_quote($data['section']) . '%')->count();

            if($record === 0){
                  return Section::create([
                            'section' => $data['section'],
                            'grade_level_id' => $data['grade_level_id']
                        ]);
            } else {
                return ResponseHelper::response_error('Section name already exists.', []);
            }
          
            
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('User Creation Failed');
        }
    }

    public function updateSection($data, $id)
    {   
        if (!$data) {
            throw new CustomBadRequestException('No fields to create');
        }
        $section = $this->model->where('id', $id)->first();
        $section->section = Arr::get($data, 'section', $section->section);
        $section->save();

        return $section->fresh('gradeLevel');
        try
        {   
           
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('Section Update Failed');
        }
    }
}