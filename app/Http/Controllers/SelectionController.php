<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SelectionController extends Controller
{
    /**
     * Display the subjects selection.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function subjectSelection(Request $request)
    {
        $model = Subject::with(['gradeLevel' => function($query){
             $query->select('id','grade_level');
        }]);

        if($request->has('grade_level_id')){
            $model = $model->where('grade_level_id', $request->grade_level_id);
        }

        $data = $model->get()
                ->map(function($query){
                    return [
                        'id' => $query->id,
                        'code' => $query->code,
                        'title' => $query->title,
                        'grade_level' => $query->gradeLevel->grade_level,
                    ];
                });

        return ResponseHelper::response_success('Subject Selection', $data);
    }

    /**
     * Display the school year selection.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function schoolYearSelection(Request $request)
    {
        $data = SchoolYear::all()
        ->map(function ($sy){
            return [
                'key' => $sy->school_year,
                'value' => $sy->id,
                'selected' => false,
            ];
        });

        return ResponseHelper::response_success('School Year Selection', $data);
    }

    /**
     * Display the grade levels selection.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function gradeLevelSelection(Request $request)
    {
        $data = GradeLevel::all()
        ->map(function ($query){
            return [
                'key' => $query->grade_level,
                'value' => $query->id,
                'selected' => false,
            ];
        });

        return ResponseHelper::response_success('Grade Level Selection', $data);
    }

     /**
     * Display the sections selection.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function sectionsSelection(Request $request)
    {   
        $model = Section::orderBy('id');
        if($request->filled('grade_level_id')){
            $model = $model->where('grade_level_id', $request->grade_level_id);
        }

        $data = $model->get()->map(function ($query){
            return [
                'key' => $query->section,
                'value' => $query->id,
                'selected' => false,
            ];
        });

        return ResponseHelper::response_success('Grade Level Selection', $data);
    }
}
