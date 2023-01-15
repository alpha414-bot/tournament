<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // switch($request->role){
        //     case('teacher'):
        //         $data = $this->userService->findTeachers($request);
        //         break;
        //     case('student'):
        //         $data = $this->userService->findStudents($request);
        //         break;
        // }
        $data = $this->userService->findTeachers($request);

        return ResponseHelper::response_success('Users', $data);
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
            'password' => 'required',
            'address' => 'required',
            'birth_date' => 'nullable',
            'mobile' => 'required',
            'learners_number' => 'nullable',
            'instructor_number' => 'nullable',
            'contact_name' => 'nullable',
            'contact_number' => 'nullable',
            'contact_relationship' => 'filled',
            'admission_type' => 'filled',
            'role' => 'filled',
            
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Create User Failed', $validator->messages());
        }
        $data = $this->userService->createUser($request->all());

        return ResponseHelper::response_created('User Created', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        $data = $this->userService->updateUser($id,$request->all());

        return ResponseHelper::response_created('User Updated Successfully', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
