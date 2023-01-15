<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomBadRequestException;
use App\Helpers\ResponseHelper;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class StudentSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'student_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('View Student Subjects Failed', $validator->messages());
        }
        
        try {

            $model = StudentSubject::with(['classList' => function($query) use ($request){
                    $query->with(['subject','section','teacher','schoolYear']);
            }])
            ->where('student_id', $request->student_id);
            if($request->filled('school_year_id')){
                $model = $model->whereHas('classList', function($q) use ($request){
                    $q->where('school_year_id', '=', $request->school_year_id);
                });
            }

            $model = $model->get()
            ->map(function ($query){
                $instructor = $query->teacher ? $query->teacher->first_name . " " . $query->teacher->last_name : 'No assigned yet';
                    return [
                        'id'            => $query->id,
                        'section'       => Arr::get($query, 'classList.section.section'),
                        'instructor'    => $instructor,
                        'subject'       => Arr::get($query, 'classList.subject.title'),
                        'days'          => Arr::get($query, 'days', 'None'),
                        'start_time'    => Arr::get($query, 'start_time', 'None'),
                        'end_time'      => Arr::get($query, 'end_time', 'None'),
                        'school_year_id'=> Arr::get($query, 'classList.schoolYear.school_year_id'),
                        'school_year'   => Arr::get($query, 'classList.schoolYear.school_year'),
                        'created_at'    => Arr::get($query, 'created_at'),
                    ];
                });

            return ResponseHelper::response_success('View Student Subjects', $model);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('View Student Subjects Failed');
        }
    }

    public function getSubjectGrades(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'student_id' => 'required',
            'school_year_id' => 'filled',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('View Student Subjects Failed', $validator->messages());
        }
        
        try {

            $model = StudentSubject::with(['classList' => function($query) use ($request){
                    $query->with(['subject','section','teacher','schoolYear']);
            }])
            ->where('student_id', $request->student_id);

            if($request->filled('school_year_id')){
                $model = $model->whereHas('classList', function($q) use ($request){
                    $q->where('school_year_id', '=', $request->school_year_id);
                });
            }

            $data = $model->get()
            ->map(function ($query){
                $instructor = $query->classList->teacher ? $query->classList->teacher->first_name . " " . $query->classList->teacher->last_name : 'No assigned yet';
                    return [
                        'id'            => $query->id,
                        'section'       => Arr::get($query, 'classList.section.section'),
                        'subject'       => Arr::get($query, 'classList.subject.title'),
                        'instructor'    => $instructor,
                        'first'         => Arr::get($query, 'first', 0),
                        'second'        => Arr::get($query, 'second', 0),
                        'third'         => Arr::get($query, 'third', 0),
                        'fourth'        => Arr::get($query, 'fourth', 0),
                        'total_average' => Arr::get($query, 'total_average', 0),
                        'remarks'       => Arr::get($query, 'remarks', 0)
                    ];
                });

            return ResponseHelper::response_success('View Student Subjects', $data);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('View Student Subjects Failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignSubjects(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'enrollment_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Create User Failed', $validator->messages());
        }
        
        try {
            $subjects = collect($request->get('subjects'));
            $newSubjects = $subjects->map(function ($query) use ($request){
                $model = StudentSubject::where([
                    'enrollment_id' => Arr::get($request,'enrollment_id'),
                    'subject_id'    => Arr::get($query,'id'),
                ])->first();

                if (!$model) {
                    return StudentSubject::create([
                        'enrollment_id' => Arr::get($request,'enrollment_id'),
                        'subject_id'    => Arr::get($query,'id'),
                        'first'         => 0,
                        'second'        => 0,
                        'third'         => 0,
                        'fourth'        => 0,
                        'total_average' => 0
                    ]);
                }
            });

            $newSubjects = collect($newSubjects)
            ->map(function ($query){
               return $query->id;
            });

            $data = StudentSubject::whereIn('id', $newSubjects)
            ->with('subject')
            ->get()
            ->map(function ($query){
                return [
                    'id'            => $query->id,
                    'subject'       => $query->subject->title,
                    'first'         => $query->first,
                    'second'        => $query->second,
                    'third'         => $query->third,
                    'fourth'        => $query->fourth,
                    'total_average' => $query->total_average,
                    'remarks'       => $query->remarks
                ];
            });

            return ResponseHelper::response_created('Assign Student\'s Subjects Successfully', $data);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('Assign Subjects Failed');
        }
    }

    /**
     * Display the available selection of subjects.
     *
     * @return \Illuminate\Http\Response
     */
    public function subjectSelection(Request $request)
    {
        try{
            $existingSubjects = StudentSubject::select('subject_id')
            ->where('enrollment_id', $request->enrollment_id)
            ->get()
            ->map(function($query){
                return $query->subject_id;
            });

            $model = Subject::with(['gradeLevel' => function ($query){
                $query->select('id','grade_level');
            }])
            // ->where('grade_level_id', $request->grade_level_id)
            ->whereNotIn('id', $existingSubjects)
            ->get()
            ->map(function ($query){
                return [
                    'id'            => $query->id,
                    'code'          => $query->code,
                    'title'         => $query->title,
                    'grade_level'   => $query->gradeLevel->grade_level
                ];
            });
            $data = $model;
            return ResponseHelper::response_success('Available Subjects', $data);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('Assign Subjects Failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function show(StudentSubject $studentSubject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentSubject $studentSubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentSubject $studentSubject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function removeSubject(Request $request, $id)
    {  
        if(!$id){
            throw new CustomBadRequestException('Invalid Student Subject ID');
        }

        try{
            StudentSubject::where('id', $id)->delete();

            return ResponseHelper::response_success('Subject removed successfully', []);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('Assign Subjects Failed');
        }
    }

    public function setGrades(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'first' => 'filled',
            'second' => 'filled',
            'third' => 'filled',
            'fourth' => 'filled',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Assign Subjects Failed', $validator->messages());
        }
        try{
            $model =  StudentSubject::where('id', $id)->first();
            // return 
            if($request->filled('first')){
                $model->first = Arr::get($request,'first', $model->first);
            }
            if($request->filled('second')){
                
                $model->second = Arr::get($request,'second',  $model->second);
            }
            if($request->filled('third')){
                
                $model->third = Arr::get($request,'third', $model->third);
            }
            if($request->filled('fourth')){
                
                $model->fourth = Arr::get($request,'fourth', $model->fourth);
            }

            //Get total average
            $average = [$model->first, $model->second, $model->third, $model->fourth];
            $total_average = array_sum($average) / count($average);

            $model->total_average = $total_average > 0 ? $total_average : $model->total_average;
            $model->save();
            $query = $model->fresh([
                'classList' => function($query){
                    $query->with(['subject','section','teacher','schoolYear']);
                },
                'student',
            ]);

            $model = [
                'id'                =>  $query->id,
                'learners_number'   =>  $query->student->learners_number,
                'first_name'        =>  $query->student->first_name,
                'last_name'         =>  $query->student->last_name,
                'first'             =>  Arr::get($query,'first', 0),
                'second'            =>  Arr::get($query,'second', 0),
                'third'             =>  Arr::get($query,'third', 0),
                'fourth'            =>  Arr::get($query,'fourth', 0),
                'total_average'     =>  Arr::get($query,'total_average', 0),
                'remarks'           =>  $query->remarks
            ];
            
            return ResponseHelper::response_success('Grades Updated Successfully', $model);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('Assign Subjects Failed');
        }
        
    }
}
