<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->studentService->findAll($request);

        return ResponseHelper::response_success('Students', $data);
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
            'email' => 'required|unique:users',
            'address' => 'required',
            'birth_date' => 'required',
            'mobile' => 'required',
            'instructor_number' => 'filled',
            'contact_name' => 'nullable',
            'contact_number' => 'nullable',
            'contact_relationship' => 'filled',
            'admission_type' => 'filled',
            'grade_level' => 'filled',
            'role' => 'filled',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Create User Failed', $validator->messages());
        }
        $data = $this->studentService->createUser($request->all());

        return ResponseHelper::response_created('Student Created', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->studentService->findById($id);

        return ResponseHelper::response_success('Student Data', $data);
    }

    /**
     * TODO: Get assigned subjects.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function getMySubjects(Request $request, $id)
    {
        $data = $this->studentService->getMySubjects($request, $id);

        return ResponseHelper::response_success('Student Data', $data);
    }

    public function getStudentsSelection(Request $request)
    {
        $data = $this->studentService->getStudentsSelection($request);

        return ResponseHelper::response_success('Students Selection', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'birth_date' => 'required',
            'mobile' => 'required',
            'instructors_number' => 'filled',
            'contact_name' => 'nullable',
            'contact_number' => 'nullable',
            'contact_relationship' => 'filled',
            'admission_type' => 'filled',
            'grade_level' => 'filled',
            'role' => 'filled',
            'changePassword' => 'filled'
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Update User Failed', $validator->messages());
        }
        $data = $this->studentService->updateStudent($id,$request->all());

        return ResponseHelper::response_created('User Updated Successfully', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
