<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomBadRequestException;
use App\Helpers\ResponseHelper;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\StudentSubject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    /**
     * Set Enrollment as default model.
     *
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->model = $enrollment;
    }

    /**
     * GET default query.
     *
     */
    public function model($id)
    {   
        $model = $this->model;
        $model = $model->where('user_id', $id)
        ->with([
                'student' => function($query){
                    $query->select('id');
                },
                'section' => function($query){
                    $query->select('id','section');
                },
                'school_year' => function($query){
                    $query->select('id','school_year');
                },
                ])->orderBy('id','desc');
        return $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {   
        // return $request->school_year_id;
        
        try{
            $model = $this->model($id);

            if($request->filled('school_year_id')){
                $model = $model->where('school_year_id', $request->school_year_id);
            }
            
            $model = $model
            ->get()
            ->map(function($query){
                return [
                    'id'            => Arr::get($query, 'id'),
                    'section'       => Arr::get($query, 'section.section'),
                    'school_year'   => Arr::get($query, 'school_year.school_year'),
                    'date_enrolled' => Carbon::parse($query->created_at)->format('Y-m-d'),
                ];
            });

            return ResponseHelper::response_created('Student\'s Enrollment Record', $model);
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('Something went wrong');
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'section_id' => 'required',
            'school_year_id' => 'required'
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Create User Failed', $validator->messages());
        }
        
        try {
            Enrollment::create([
                'user_id'           => Arr::get($request,'user_id'),
                'section_id'        => Arr::get($request,'section_id'),
                'grade_level_id'    => Arr::get($request,'grade_level_id'),
                'school_year_id'    => Arr::get($request,'school_year_id'),
                'status'            => Arr::get($request,'status', 'done'),
            ]);

            $model = $this->model($request->user_id);
            $data = $model
                ->get()
                ->map(function($query){
                return [
                    'id'            => Arr::get($query, 'id'),
                    'section'       => Arr::get($query, 'section.section'),
                    'school_year'   => Arr::get($query, 'school_year.school_year'),
                    'date_enrolled' => Carbon::parse($query->created_at)->format('Y-m-d')
                ];
            })->first();


            return ResponseHelper::response_created('Student enrolled successfully', $data);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('Student Enrollment Failed');
        }

       
    }
    
    /**
     * Assign subjects
     *
     * @return \Illuminate\Http\Response
     */
    public function assignSubjects(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'enrollment_id' => 'required',
            'subject_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Create User Failed', $validator->messages());
        }
        
        try {

            StudentSubject::create([
                'enrollment_id' => Arr::get($request,'enrollment_id'),
                'subject_id' => Arr::get($request,'subject_id'),
                'first' => 0,
                'second' => 0,
                'third' => 0,
                'fourth' => 0,
                'total_average' => 0
            ]);

            $data = StudentSubject::where('enrollment_id', $request->enrollment_id)
            ->with('subject')
            ->get()
            ->map(function ($query){
                return [
                    'subject'       => $query->subject->title,
                    'first'         => $query->first,
                    'second'        => $query->second,
                    'third'         => $query->third,
                    'fourth'        => $query->fourth,
                    'total_average' => $query->total_average,
                ];
            });

            return ResponseHelper::response_created('Assign Student\'s Subjects Successfully', $data);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('Assign Subjects Failed');
        }
    }
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {   
        $data = Student::find($request->id)
        ->enrollments()
        ->first();
        

        return ResponseHelper::response_success('Student\'s Enrollment', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function edit(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Enrollment $enrollment)
    {
        //
    }
}
