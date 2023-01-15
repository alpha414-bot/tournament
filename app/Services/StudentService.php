<?php

namespace App\Services;

use App\Exceptions\CustomBadRequestException;
use App\Models\ClassList;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Self_;

class StudentService
{
    public function __construct(Student $user)
    {
        $this->model = $user::where('role', 'student');
    }
  
    public function findAll($request){
        try{
            $model = $this->model;
            
            if ($request->has('search')) {
                $model = $model->where(function ($query) use ($request) {
                            $query->where('first_name', 'like', '%' . $request->search . '%' )
                                ->orWhere('last_name', 'like', '%' . $request->search . '%' )
                                ->orWhere('learners_number', 'like', '%' . $request->search . '%' );
                        });
            }
            
            if ($request->filled('role')) {
                
                $model = $model->where('role', $request->role);
            }
            
            $model = $model->orderByDesc('created_at')
                        ->get()
                        ->map(function($student){
                            return [
                                'id' => $student->id,
                                'learners_number' => $student->learners_number ? $student->learners_number : 'None',
                                'first_name' => $student->first_name,
                                'last_name' => $student->last_name
                            ];
                        });
                    
    
             return $model;
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('User Creation Failed');
        }
    }

    public function findById($id)
    {
        try{

            $model = $this->model->where('id',$id);
            
            $model = $model
                    ->with('gradeLevel')
                    ->with('section')
                    ->first();
                    
    
             return $model;
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('User Creation Failed');
        }
    }

    public function getStudentsSelection($request)
    {
        
        $grade_level_id = GradeLevel::gradeLevel($request->grade_level)->id;
        // $section_id = Section::getSection()->id;
        // return $section_id;
        $model = $this->model->where('grade_level_id', $grade_level_id)
                          ->where(function($query) use ($grade_level_id,$request){
                          $query->where('section_id', '!=', $request->section_id)
                            ->orWhereNull('section_id');
                        })
                        ->get()
                        ->map(function ($student){
                            return [
                                'id' => $student->id,
                                'name' => $student->first_name . ' ' .  $student->last_name
                            ];
                        });
        
        // $model = $model->with('gradeLevel')->with('section.classLists.subjects')->first();
                

         return $model;
        try{

            $grade_level_id = GradeLevel::gradeLevel($request->grade_level)->id;
            $section_id = Section::getSection($request->section)->id;

            $model = $this->model->where('grade_level_id', $grade_level_id)
                            ->whereNot('section_id', $section_id)
                            ->get()
                            ->map(function ($student){
                                return [
                                    'id' => $student->id,
                                    'name' => $student->first_name . ' ' .  $student->last_name
                                ];
                            });
            
            // $model = $model->with('gradeLevel')->with('section.classLists.subjects')->first();
                    
    
             return $model;
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('User Creation Failed');
        }
    }

    public function createUser($data)
    {
        try{

            $defaultPassword = 'portal';

            $grade_level_id = GradeLevel::gradeLevel($data['grade_level'])->id;


            return User::create([
                'password' => Hash::make($defaultPassword),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'address' => $data['address'],
                'birth_date' => isset($data['birth_date']) ?  $data['birth_date'] : null,
                'mobile' => $data['mobile'],
                'learners_number' => isset($data['learners_number']) ? $data['learners_number'] : null,
                'contact_name' => isset($data['contact_name']) ? $data['contact_name'] : null,
                'contact_number' => isset($data['contact_number']) ? $data['contact_number'] : null,
                'contact_relationship' => isset($data['contact_relationship']) ? $data['contact_relationship'] : null,
                'admission_type' => $data['admission_type'],
                'grade_level_id' => $grade_level_id,
                'role' => $data['role'],
            ]);
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException("User Creation Failed {$e}");
        }
    }
   
    public function updateStudent($id, $data){

        if (!$data) {
            throw new CustomBadRequestException('No fields to update');
        }

        // try
        // {
            $model = $this->model;
            
            $user = $model->where('id', $id)->firstOrFail();

            if($data['changePassword'] ===  true){
                $defaultPassword='portal';
                $user->password = Hash::make($defaultPassword);
            }

            $grade_level_id = GradeLevel::gradeLevel($data['grade_level'])->id;

            $user->first_name = Arr::get($data, 'first_name',  $user->first_name);
            $user->last_name = Arr::get($data, 'last_name',  $user->last_name);
            $user->email = Arr::get($data, 'email',  $user->email);
            $user->mobile = Arr::get($data, 'mobile',  $user->mobile);
            $user->birth_date = Arr::get($data, 'birth_date',  $user->birth_date);
            $user->learners_number = Arr::get($data, 'learners_number',  $user->learners_number);
            $user->address = Arr::get($data, 'address',  $user->address);
            $user->role = Arr::get($data, 'role',  $user->role);
            $user->contact_name = Arr::get($data, 'contact_name',  $user->contact_name);
            $user->contact_number = Arr::get($data, 'contact_number',  $user->contact_number);
            $user->contact_relationship = Arr::get($data, 'contact_relationship',  $user->contact_relationship);
            $user->admission_type = Arr::get($data, 'admission_type',  $user->admission_type);
            $user->grade_level_id = isset($grade_level_id) ? $grade_level_id : $user->grade_level_id;
            $user->save();

            return $user->fresh(['gradeLevel', 'section.classLists.subject']);
        // }
        // catch(\Throwable $e){
        //     throw new CustomBadRequestException('User Creation Failed');
        // }

    }

    // Teacher Class Loads
    public function getClassLoads($request, $id)
    {
        try{
            
            $model = $this->model;

            $model = $model
                    ->with(['classLists.subjects' => function ($query){
                         $query->select('id','code','title');
                    }])
                    ->where('id', $id)
                    ->first();
                    // ->map(function($teacher){
                    //     // $this->withoutWrapping();
                    //     return [
                    //         'id' => $teacher->id,
                    //         'learners_number' => $teacher->learners_number,
                    //         'first_name' => $teacher->first_name,
                    //         'last_name' => $teacher->last_name,
                    //         'classes' => $teacher->classLists
                    //     ];
                    // });

            return $model;
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('User Details Failed');
        }
    }

    public function updateClass($id, $data)
    {
        if (!$data) {
            throw new CustomBadRequestException('No fields to update');
        }
        $model = ClassList::findOrFail($id);

        $model->teacher_id = $data['teacher_id'] === "null" ? null : Arr::get($data, 'teacher_id',  $model->teacher_id);
        $model->save();

        return $model->fresh();

        try{
           
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('User Creation Failed');
        }
    }

    public function getMySubjects($request, $id){

        try{
            $model = StudentSubject::where('student_id', $id)
            ->with([
                'classList' => function($query){
                    $query->with(['subject','section','teacher','schoolYear']);
                },
                'student',
            ])->get();
 
            $model = $model->map( function ($query){
                 return [
                     'id'            =>  $query->id,
                     'learners_number' => $query->student->learners_number,
                     'first_name'    =>  $query->student->first_name,
                     'last_name'     =>  $query->student->last_name,
                     'first'         =>  Arr::get($query,'first', 0),
                     'second'        =>  Arr::get($query,'second', 0),
                     'third'         =>  Arr::get($query,'third', 0),
                     'fourth'        =>  Arr::get($query,'fourth', 0),
                     'total_average' =>  Arr::get($query,'total_average', 0),
                     'remarks'       =>  $query->remarks
                 ];
            });
            return $model;
         }
         catch(\Throwable $e){
             
             throw new CustomBadRequestException('Add Students to Class Failed');
         }
    }
}