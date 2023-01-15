<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\Grade;
use App\Exceptions\CustomBadRequestException;
use App\Models\GradeLevel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class SubjectService
{
    protected $model;

    public function __construct(Subject $subject)
    {
        $this->model = $subject;
    }

    public function findAll($request){

        $model = $this->model
                ->with(['gradeLevel' => function ($query){
                return $query->select('id','grade_level');
                }]);
                
        // $model = $this->model
        //             ->leftJoin('grade_levels', 'subjects.grade_level_id','=','grade_levels.id')
        //             ->select('subjects.code','subjects.title','subjects.units','grade_levels.grade_level');
      
        if ($request->has('search')) {
            $model = $model->where(function ($query) use ($request) {
                $query->where('code', 'like', '%' . $request->search . '%' )
                      ->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('grade_level')) {
            $gradeLevel = $this->gradeLevel($request['grade_level']);
            $model = $model
                     ->where('grade_level_id', $gradeLevel);
        }


        
       
        return $model->get();
            
        // $model = $model::select('code','title','units')
        //     ->join('grade_levels', 'grade_levels.id' ,'=','subjects.grade_level_id')
        //     ->orderBy('grade_levels.grade_level','asc')
        //     ->get();
        
        
    }

    public function createSubject($data){

        if(!$data){
            
            throw new CustomBadRequestException('Subject Creation Failed');
        }

        try{
            $gradeLevel = $this->gradeLevel($data['grade_level']);
            return Subject::create([
                "code" => $data['code'],
                "title" => $data['title'],
                "units" => $data['units'],
                "grade_level_id" => $gradeLevel,
            ]);
        }
        catch(\Throwable $e){
            
            throw new CustomBadRequestException('Subject Creation Failed');
        }
    }

    public function editSubject($id, $data)
    {
      
       if (!$data) {
        throw new CustomBadRequestException('No fields to update');
         }
        try {
            $gradeLevel = Self::gradeLevel($data['grade_level']);
            // return $gradeLevel;
            $subject = Subject::where('id', $id)->first();
            $subject->code = Arr::get($data, 'code',  $subject->code);
            $subject->title = Arr::get($data, 'title',  $subject->title);
            $subject->units = Arr::get($data, 'units',  $subject->units);
            $subject->grade_level_id = $gradeLevel;
            $subject->save();

            return $subject->fresh();
        } catch (\Throwable $e) {
            throw new CustomBadRequestException('Cannot Update Subject');
        }
    }

    public static function gradeLevel($level)
    {
        return GradeLevel::where('grade_level', $level)->first()->id;
    }
}