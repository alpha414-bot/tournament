<?php

namespace App\Services;

use App\Exceptions\CustomBadRequestException;
use App\Models\ClassList;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Self_;

class TeacherService
{
    public function __construct(Teacher $user)
    {
        $this->model = $user::where('role', 'teacher');
    }
  
    public function findAll($request){
        try{
            $model = $this->model;
            
            if ($request->has('search')) {
                $model = $model->where(function ($query) use ($request) {
                            $query->where('first_name', 'like', '%' . $request->search . '%' )
                                ->orWhere('last_name', 'like', '%' . $request->search . '%' )
                                ->orWhere('instructor_number', 'like', '%' . $request->search . '%' );
                        });
            }
            
            if ($request->filled('role')) {
                
                $model = $model->where('role', $request->role);
            }
            
            $model = $model->orderByDesc('created_at')
                        ->get()
                        ->map(function($teacher){
                            return [
                                'id' => $teacher->id,
                                'instructor_number' => $teacher->instructor_number ? $teacher->instructor_number : 'None',
                                'first_name' => $teacher->first_name,
                                'last_name' => $teacher->last_name
                            ];
                        });
                    
    
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

            return User::create([
                'password' => Hash::make($defaultPassword),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'address' => $data['address'],
                'birth_date' => isset($data['birth_date']) ?  $data['birth_date'] : null,
                'mobile' => $data['mobile'],
                'learners_number' => isset($data['learners_number']) ? $data['learners_number'] : null,
                'instructor_number' => isset($data['instructor_number']) ? $data['instructor_number'] : null,
                'contact_name' => isset($data['contact_name']) ? $data['contact_name'] : null,
                'contact_number' => isset($data['contact_number']) ? $data['contact_number'] : null,
                'contact_relationship' => isset($data['contact_relationship']) ? $data['contact_relationship'] : null,
                'admission_type' => $data['admission_type'],
                'role' => $data['role'],
            ]);
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException("User Creation Failed {$e}");
        }
    }
   
    public function updateUser($id, $data){

        if (!$data) {
            throw new CustomBadRequestException('No fields to update');
        }

        try
        {
            $model = $this->model;
            
            $user = $model->where('id', $id)->firstOrFail();
            if($data['changePassword'] ===  true){
                
                $defaultPassword='portal';
                $user->password = Hash::make($defaultPassword);
            }
            $user->first_name = Arr::get($data, 'first_name',  $user->first_name);
            $user->last_name = Arr::get($data, 'last_name',  $user->last_name);
            $user->email = Arr::get($data, 'email',  $user->email);
            $user->mobile = Arr::get($data, 'mobile',  $user->mobile);
            $user->birth_date = Arr::get($data, 'birth_date',  $user->birth_date);
            $user->instructor_number = Arr::get($data, 'instructor_number',  $user->instructor_number);
            $user->address = Arr::get($data, 'address',  $user->address);
            $user->role = Arr::get($data, 'role',  $user->role);
            $user->contact_name = Arr::get($data, 'contact_name',  $user->contact_name);
            $user->contact_number = Arr::get($data, 'contact_number',  $user->contact_number);
            $user->contact_relationship = Arr::get($data, 'contact_relationship',  $user->contact_relationship);
            $user->save();

            return $user->fresh();
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('User Creation Failed');
        }

    }

    

    // Teacher Class Loads
    public function getClassLoads($request, $id)
    {
        try{
            
            $model = $this->model;

            $model = $model
                    ->with([
                        'classLists.subject' => function($query){
                            $query->orderBy('created_at','desc');
                        },
                        'classLists.section'])
                    ->where('id', $id)
                    ->get();
                    // ->map(function($teacher){
                    //     return [
                    //         'id' => $teacher->id,
                    //         'instructor_number' => $teacher->instructor_number,
                    //         'first_name' => $teacher->first_name,
                    //         'last_name' => $teacher->last_name,
                    //         'class_lists' => $teacher->classLists->map(function ($query){
                    //             return [
                    //                 'id'            => $query->id,
                    //                 'section'       => $query->section->section,
                    //                 'subject'       => $query->subject->title,
                    //                 'days'          => $query->days,
                    //                 'start_time'    => $query->start_time,
                    //                 'end_time'      => $query->end_time,
                    //             ];
                    //         })->sortBy(['created_at','desc'])
                    //     ];
                    // })->first();

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

        $model->teacher_id = Arr::get($data, 'teacher_id',  $model->teacher_id);
        $model->save();

        return $model->fresh();

        try{
           
        }
        catch(\Throwable $e){
            throw new CustomBadRequestException('User Creation Failed');
        }
    }


}