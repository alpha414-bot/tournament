<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomBadRequestException;
use App\Helpers\ResponseHelper;
use App\Models\ClassList;
use App\Models\StudentSubject;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Laravel\Ui\Presets\React;
use Carbon\Carbon;

class TeacherController extends Controller
{
    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->teacherService->findAll($request);

        return ResponseHelper::response_success('Teacher', $data);
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'birth_date' => 'required',
            'mobile' => 'required',
            'instructor_number' => 'filled',
            'contact_name' => 'nullable',
            'contact_number' => 'nullable',
            'contact_relationship' => 'filled',
            'admission_type' => 'filled',
            'role' => 'filled',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Create User Failed', $validator->messages());
        }
        $data = $this->teacherService->createUser($request->all());

        return ResponseHelper::response_created('User Created', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */

    public function show($id){
        try {
            $data = Teacher::where('id', $id)->first();
            return ResponseHelper::response_success('Teachers', $data);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('User Details Failed');
        }
    }

    public function classResource($model)
    {
        return $model->map(function ($query){
            return [
                'id'            => $query->id,
                'section'       => $query->section->section,
                'subject'       => $query->subject->title,
                'days'          => $query->days,
                'start_time'    => Carbon::parse($query->start_time)->format('h:ia'),
                'end_time'      => Carbon::parse($query->end_time)->format('h:ia'),
                'created_at'    => $query->created_at,
                'school_year_id'=> $query->school_year_id
            ];
        });
    }

    public function getClasses(Request $request, $id){
        try{

            $model = ClassList::where('teacher_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->with(['subject','section']);
        
           if($request->filled('school_year_id')){
               $model = $model->where('school_year_id', $request->school_year_id);
           }

            $model = $model->get();
            $data = $this->classResource($model);
            return ResponseHelper::response_success('Teacher\'s classes', $data);
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('Assign Subjects Failed');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {  
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'birth_date' => 'required',
            'mobile' => 'required',
            'instructor_number' => 'filled',
            'contact_name' => 'nullable',
            'contact_number' => 'nullable',
            'contact_relationship' => 'filled',
            'admission_type' => 'filled',
            'role' => 'filled',
            'changePassword' => 'filled'
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Update User Failed', $validator->messages());
        }
        $data = $this->teacherService->updateUser($id,$request->all());

        return ResponseHelper::response_created('User Updated Successfully', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        //
    }

    public function updateClass($id, Request $request)
    {   
        // return [$id, $request];
        $payload = $request->validate([
            'teacher_id' => 'filled',
        ]);

        $data = $this->teacherService->updateClass($id, $payload);

        return ResponseHelper::response_success('Subject Updated', $data);
    }

    public function assignSubjects(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'grade_level_id' => 'required',
            'section_id' => 'required',
            'school_year_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Assign Subjects Failed', $validator->messages());
        }

        try{
            $subjects = collect($request->get('subjects'));
            $newClass = $subjects->map(function ($query) use ($request){

                return ClassList::updateOrCreate(
                    [
                        'subject_id'    => Arr::get($query,'id'),
                        'grade_level_id' => Arr::get($request,'grade_level_id'),
                        'section_id'    =>  Arr::get($request,'section_id'),
                        'school_year_id'    =>  Arr::get($request,'school_year_id'),
                    ],
                    [
                        'teacher_id'    =>  Arr::get($request,'user_id'),
                    ]);
            });

            $newClass = collect($newClass)
            ->map(function ($query){
               return $query->id;
            });

            $data = ClassList::whereIn('id', $newClass)
            ->with(['subject','section'])
            ->get()
            ->map(function ($query){
                return [
                    'id'            => $query->id,
                    'section'       => $query->section->section,
                    'subject'       => $query->subject->title,
                    'days'          => $query->days,
                    'start_time'    => $query->start_time,
                    'end_time'      => $query->end_time,
                    'school_year_id'=> $query->school_year_id,
                ];
            });

            return ResponseHelper::response_created('Assigned Subjects Successfully', $data);
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('Assign Subjects Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function removeSubject($id)
    {  
        if(!$id){
            throw new CustomBadRequestException('Invalid Class ID');
        }

        try{
            $class =  ClassList::where('id', $id)->firstOrFail();
            $class->teacher_id = null;
            $class->save();
            
            return ResponseHelper::response_success('Subject removed successfully', []);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('Assign Subjects Failed');
        }
    }

    
}
