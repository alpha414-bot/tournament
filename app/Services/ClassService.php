<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\Grade;
use App\Exceptions\CustomBadRequestException;
use App\Mail\MyTestMail;
use App\Models\ClassList;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class ClassService
{
    protected $model;

    public function __construct(ClassList $classList)
    {
        $this->model = $classList;
    }
    public function classResource($model){
        return $model->get()->map(function ($query){
            $instructor = $query->teacher ? $query->teacher->first_name . " " . $query->teacher->last_name : 'No assigned yet';
            return [
                'id'            => $query->id,
                'section'       => $query->section->section,
                'instructor'    => $instructor,
                'subject'       => $query->subject->title,
                'days'          => $query->days,
                'start_time'    => Carbon::parse($query->start_time)->format('h:ia'),
                'end_time'      => Carbon::parse($query->end_time)->format('h:ia'),
                'school_year_id'=> $query->school_year_id,
                'created_at'    => $query->created_at
            ];
        });
    }
    public function classStudentsResource($model){
        return $model->get()->map( function ($query){
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
    }
    // Admin
    public function findAll($request){

        $model = ClassList::orderBy('created_at', 'desc')
        ->with(['subject','section','teacher','schoolYear']);
        
        if($request->filled('teacher_id')){
            $model = $model->where('teacher_id', $request->teacher_id);
        }
        if($request->filled('school_year_id')){
            $model = $model->where('school_year_id', $request->school_year_id);
        }
        if($request->filled('grade_level_id')){
            $model = $model->where('grade_level_id', $request->grade_level_id);
        }
        if($request->filled('section_id')){
            $model = $model->where('section_id', $request->section_id);
        }

        $model = $this->classResource($model);
        return $model;
    }

    public function createClass($request){
        try{
            $subjects = collect($request->subjects);

            $newClass= $subjects->map(function($subject) use ($request){

                return ClassList::updateOrCreate(
                    [
                        'grade_level_id' => Arr::get($request,'grade_level_id'),
                        'section_id'    =>  Arr::get($request,'section_id'),
                        'school_year_id'    =>  Arr::get($request,'school_year_id'),
                        'subject_id'    => Arr::get($subject,'id'),
                    ],
                    [
                        'subject_id'    => Arr::get($subject,'id'),
                    ]);
            });

            $newClass = collect($newClass)
            ->map(function ($query){
               return $query->id;
            });

            $model = ClassList::whereIn('id', $newClass)
            ->with(['subject','section']);

            $model = $this->classResource($model);
            return $model;
        }
        catch(\Throwable $e){
            
            throw new CustomBadRequestException('Subject Creation Failed');
        }
    }

    public function editClass($id, $data)
    {
      
        if (!$data) {
        throw new CustomBadRequestException('No fields to update');
         }
        try {
            $class = ClassList::where('id', $id)->first();
            $class->days = Arr::get($data, 'days',  $class->days);
            $class->start_time = Arr::get($data, 'start_time',  $class->start_time);
            $class->end_time = Arr::get($data, 'end_time',  $class->end_time);
            $class->save();

            $model = $this->model->where('id', $id);
            $model = $this->classResource($model)->first();

            return $model;

        } catch (\Throwable $e) {
            throw new CustomBadRequestException('Update Class Schedule Failed');
        }
    }

    public function getClassStudents($request){
        try{
           $model = StudentSubject::where('class_id', $request->class_id)
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

    public function addClassStudents($request){

         try{
            $students = collect($request->students);
            $newClass= $students->map(function($student) use ($request){

                return StudentSubject::updateOrCreate(
                    [
                        'student_id' => Arr::get($student,'id'),
                        'class_id' => Arr::get($request,'class_id'),
                    ],
                    [
                        'student_id' => Arr::get($student,'id'),
                        
                    ]);
            });

            $newClassStudents = collect($newClass)
            ->map(function ($query){
               return $query->id;
            });

            $model = StudentSubject::whereIn('id', $newClassStudents)
            ->with([
                'classList' => function($query){
                    $query->with(['subject','section','teacher','schoolYear']);
                },
                'student',
            ]);

            $model = $this->classStudentsResource($model);
            return $model;
        }
        catch(\Throwable $e){
            
            throw new CustomBadRequestException('Add Students to Class Failed');
        } 
    }

    public function removeClassStudents($id)
    {
        try{
            $studentSubject =  StudentSubject::where('id', $id)->first();
            $studentSubject->delete();

        } catch(\Throwable $e){
            throw new CustomBadRequestException('Assign Subjects Failed');
        }
    }

    public function getAllStudents($request)
    {
        try {
            $model = Student::where('role', 'student')
            ->orderBy('last_name');
            
            if($request->filled('school_year_id')){
                $model = $model->whereDoesntHave('enrollments',function ($query) use ($request){
                    $query->where('school_year_id', '=', $request->school_year_id);
               });
            }

            if($request->has('search')){
                $model = $model->whereHas('student' ,function($q) use ($request){
                    $q->where('first_name', 'like', '%' . preg_quote($request->search) . '%')
                    ->orWhere('last_name',  'like', '%' . preg_quote($request->search) . '%')
                    ->orWhere('learners_number',  'like', '%' . preg_quote($request->search) . '%');
                });
            }
            
            
            $model = $model->get()->map(function ($query){
                    return [
                        'id'                => $query->id,
                        'learners_number'   => $query->learners_number,
                        'first_name'        => $query->first_name,
                        'last_name'         => $query->last_name
                    ];
                
            });
             
            return $model;
        } catch(\Throwable $e){
            throw new CustomBadRequestException('Student Enrollment Failed');
        }
    }
    public function getAllStudents2($request)
    {
        try {
           $model = Student::where('role', 'student')
           ->orderBy('last_name');

           if($request->has('search')){
                $model = $model->where(function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . preg_quote($request->search) . '%')
                        ->orWhere('last_name',  'like', '%' . preg_quote($request->search) . '%');
                });
            }

            if($request->filled('class_id')){
                $model = $model->whereDoesntHave('student_subjects',function ($query) use ($request){
                    $query->where('class_id', '=', $request->class_id);
               });
            }

            $model = $model->get()
            ->map(function ($query){
                    return [
                        'id'                => $query->id,
                        'learners_number'   => $query->learners_number,
                        'first_name'        => $query->first_name,
                        'last_name'         => $query->last_name
                    ];
                
            });

            return $model;
        } catch(\Throwable $e){
            throw new CustomBadRequestException('Student Enrollment Failed');
        }
    }

    //Teacher
    public function findByTeacherId($request)
    {
        try {
            $model = $this->model;

            if($request->filled('school_year')){
                $sy = SchoolYear::where('school_year', $request->school_year)->first();
                $model = $model->where('school_year_id', $sy->id);
                
            }

            $model = $model
            ->with('subjects')
            ->with('teacher')
            ->with('gradeLevel')
            ->with('section')
            ->get()
            ->map(function($class){
                $subject = $class->subjects()->first();
                $teacher = $class->teacher()->first();
                $teacher_id = isset($teacher) ? $teacher->id : 'None';
                $teacher_name = isset($teacher) ? $teacher->first_name . " " .$teacher->last_name : 'None';
                $gradeLevel = $class->gradeLevel()->first();
                $section = $class->section()->first();
                return [
                     'id' => $class->id,
                     'name' => $class->name,
                     'code' => $subject->code,
                     'title' => $subject->title,
                     'teacher_id' => $teacher_id,
                     'teacher' => $teacher_name,
                     'grade_level'  => $gradeLevel->grade_level,
                     'section'      => $section->id,
                     'days'   => $class->days,
                     'time'   => $class->start_time === 'None' ? 'None' : $class->start_time . ' - ' . $class->end_time 

                 ];
             });

            return $model;
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('Subject Classes Failed');
        }
    }

    public function showStudents($request)
    {
        // try {
            $model = $this->model;

            if($request->filled('school_year')){
                $sy = SchoolYear::where('school_year', $request->school_year)->first();
                $model = $model->where('school_year_id', $sy->id);
            }

            if($request->filled('subject')){
                // $subject = Subject::where('title', $request->subject)->first();
                $model = $model->where('subject_id', $request->subject);
            }

            $model = $model
            ->with('subjects')
            ->with('gradeLevel')
            ->with('section.students')
            ->get()
            ->map(function($class){
                $subject = $class->subjects()->first();
                $teacher = $class->teacher()->first();
                $teacher_id = isset($teacher) ? $teacher->id : 'None';
                $teacher_name = isset($teacher) ? $teacher->first_name . " " .$teacher->last_name : 'None';
                $gradeLevel = $class->gradeLevel()->first();
                $section = $class->section()->first();
                return [
                     'id'           => $class->id,
                     'name'         => $class->name,
                     'code'         => $subject->code,
                     'title'        => $subject->title,
                     'teacher_id'   => $teacher_id,
                     'teacher'      => $teacher_name,
                     'grade_level'  => $gradeLevel->grade_level,
                     'section'      => $section->id,
                     'students'     => $section->students()->get(),
                     'days'         => $class->days,
                     'time'         => $class->start_time === 'None' ? 'None' : $class->start_time . ' - ' . $class->end_time 

                 ];
             });

            return $model;
        // }
        // catch(\Throwable $e){
        //     throw new CustomBadRequestException('Subject Classes Failed');
        // }
    }

    public function addClassStudents2($request){
        //Students
        // $students = Student::whereIn('id', $request->students)->get();
        $students = collect($request->students);
        //List of classes will be added on every students
        $classes = ClassList::where('section_id', $request->section_id)
        ->where('school_year_id', $request->school_year_id)->get();

        //Get Grade Level ID
        $grade_level_id = Section::where('id', $request->section_id)->first()->grade_level_id;
        
        return $students->map(function($student) use ($classes, $request, $grade_level_id){
            
            //Check if record exists
            $record = Enrollment::where('user_id',Arr::get($student,'id'))
            ->where('school_year_id', $request->school_year_id)->first();
            
            if($record === null){
               $record = Enrollment::create([
                    'user_id'       => Arr::get($student,'id'),
                    'section_id'    => $request->section_id,
                    'grade_level_id'=> $grade_level_id,
                    'school_year_id'=> $request->school_year_id
                ]);

                // $this->sendEmail(Arr::get($student,'email'));
            }
       
            return $classes->map(function($class) use($student,$record){
                
                if($class->section_id === $record->section_id){
                    return StudentSubject::updateOrCreate(
                        [
                            'student_id' => Arr::get($student,'id'),
                            'class_id' => Arr::get($class,'id'),
                        ],
                        [
                            'student_id' => Arr::get($student,'id'),
                            
                        ]);
                }
            });
        });
    }

    public function getClassStudents2($request){
        try{
            $model = Enrollment::with('student');
            
           if($request->filled('school_year_id')){
               $model =$model->where('school_year_id', $request->school_year_id);
            }
           if($request->filled('section_id')){
                 $model = $model->where('section_id', $request->section_id);
            }
            if($request->has('search')){
                $model = $model->whereHas('student' ,function($q) use ($request){
                    $q->where('first_name', 'like', '%' . preg_quote($request->search) . '%')
                    ->orWhere('last_name',  'like', '%' . preg_quote($request->search) . '%')
                    ->orWhere('learners_number',  'like', '%' . preg_quote($request->search) . '%');
                });
            }
           
            $model = $model->get()->map(function($query){
                return [
                    'student_id'        =>  $query->student->id,
                    'learners_number'   =>  $query->student->learners_number,
                    'first_name'        =>  $query->student->first_name,
                    'last_name'         =>  $query->student->last_name,
                    'section_id'        =>  $query->section_id,
                    'school_year_id'    =>  $query->school_year_id
                ];
            });
            
           return $model;
        }
        catch(\Throwable $e){
            
            throw new CustomBadRequestException('Add Students to Class Failed');
        }
    }

    public function removeClassStudents2($request){
        try{
        
        // return $request->all();
        //Delete if record exists
        $record = Enrollment::where('user_id', $request->id)
        ->where('school_year_id', $request->school_year_id)->first();
        // return $record;
        $record->delete();
        
        //List of classes will be remove on specific student
        $classes = ClassList::where('section_id', $request->section_id)
        ->where('school_year_id', $request->school_year_id)->get();

        $classes->map(function($class) use($request){
            
            $studentSubject = StudentSubject::where([
                'student_id' => Arr::get($request,'id'),
                'class_id' => Arr::get($class,'id'),
            ])->first();
            if($studentSubject !== null){
                $studentSubject->delete();
            }
         
        });

        return [];
        }catch(\Throwable $e){
            throw new CustomBadRequestException('Remove Student in class failed');
        }
    }

    public function sendEmail($recipient){
        $details = [

            'title' => 'Student Enrolled',
    
            'body' => 'Welcome to San Jose Community High School'
    
        ];
        Mail::to($recipient)->send(new \App\Mail\MyTestMail($details));

        return new MyTestMail($details);
    }

    public function dashboard(){
            $models = DB::table('enrollments')
            ->leftJoin('school_years' ,  'enrollments.school_year_id', '=','school_years.id')
            ->select(DB::raw('count(enrollments.id) as enrolled'), 'school_years.school_year')
            ->groupBy('enrollments.school_year_id')
            ->get();
            $year = []; $not_enroll_object = ['school_year'=>[], 'total_enrolled'=>[]];
            $total_enrolled = [];
            // print_r($models->toArray());
            foreach($models as $model){
                array_push($year,$model->school_year);
                array_push($total_enrolled,$model->enrolled);
            }
            $not_year = SchoolYear::whereNotIn('school_year', $year)->get();
            if(count($not_year)>0){
                foreach($not_year as $not_enrolled){
                    $not_enroll_object['school_year'][] = $not_enrolled->school_year;
                    $not_enroll_object['total_enrolled'][] = 0;
                }
            }
            $model = [
                'school_year' => array_merge($year,$not_enroll_object['school_year']),
                'total_enrolled' => array_merge($total_enrolled,$not_enroll_object['total_enrolled']),
            ];
            // sort($model);
            return $model;
        try{
        
            
        }catch(\Throwable $e){
            throw new CustomBadRequestException('Dashboard failed');
        }
    }
}