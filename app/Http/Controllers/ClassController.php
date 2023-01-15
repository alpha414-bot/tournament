<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomBadRequestException;
use App\Models\ClassList;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\ClassService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    public function __construct(ClassService $classService)
    {
        $this->classService = $classService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->classService->findAll($request);

        return ResponseHelper::response_success('Classes', $data);
    }

    /**
     * Show Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(){
        
        $data = $this->classService->dashboard();

        return ResponseHelper::response_success('Dashboard', $data);
    }

    /**
     * Show All Student resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllStudents(Request $request)
    {
        $data = $this->classService->getAllStudents($request);

        return ResponseHelper::response_success('Student selections', $data);
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
            'grade_level_id' => 'required',
            'section_id' => 'required',
            'school_year_id' => 'required',
            'subjects' => 'required'
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Class creation Failed', $validator->messages());
        }

        $data = $this->classService->createClass($request);

        return ResponseHelper::response_created('Class Created', $data);
    }

    public function getClassStudents(Request $request){
        $validator = Validator::make($request->all(),[
            'class_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Get Class Students Failed', $validator->messages());
        }

        $data = $this->classService->getClassStudents($request);

        return ResponseHelper::response_created('Get Class Students Successfully', $data);
    }
    public function getClassStudents2(Request $request){
        

        $data = $this->classService->getClassStudents2($request);

        return ResponseHelper::response_created('Get Class Students Successfully', $data);
    }

    public function addClassStudents(Request $request){
        $validator = Validator::make($request->all(),[
            'class_id' => 'required',
            'students' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Create Class Students Failed', $validator->messages());
        }

        $data = $this->classService->addClassStudents($request);

        return ResponseHelper::response_created('Create Class Students Created', $data);
    }

    public function addClassStudents2(Request $request){
        $validator = Validator::make($request->all(),[
            'school_year_id' => 'required',
            'section_id' => 'required',
            'students' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::response_error('Create Class Students Failed', $validator->messages());
        }

        $data = $this->classService->addClassStudents2($request);

        return ResponseHelper::response_created('Create Class Students Created', $data);
    }

    public function removeClassStudents($id){

        if(!$id){
            throw new CustomBadRequestException('Invalid Student Subject ID');
        }
        $data = $this->classService->removeClassStudents($id);

        return ResponseHelper::response_created('Class Students Removed', $data);
    }
    public function removeClassStudents2(Request $request){

        $data = $this->classService->removeClassStudents2($request);

        return ResponseHelper::response_created('Class Students Removed', $data);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassList  $class
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->classService->getClassStudents($id);

        return ResponseHelper::response_success('Classes', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassList  $class
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassList $class)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassList  $class
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payload = $request->validate([
            'days' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        $requestTimeArray = $dbDaysArray = $dbTimeArray = [];
        $defaultClassList = ClassList::find($id);
        $selectDays = explode('/', $request->days);        
        $relatedClassList = ClassList::where('id', '!=', $id)->where(['school_year_id'=>$defaultClassList->school_year_id, 'grade_level_id'=>$defaultClassList->grade_level_id])->get();
        
        if(count($relatedClassList) > 0){ // Check if there are related object to the request.
            $start_time = Carbon::parse($request->start_time); $end_time = Carbon::parse($request->end_time);
            for($i=$start_time;$i<$end_time;$i->modify('+1 minutes')){
                $requestTimeArray[] = $i->format('h:i a');
            }
            for($x=0; $x<count($relatedClassList); $x++){
                $dbDaysArray[] = explode('/', $relatedClassList[$x]->days); 
                if( count(array_intersect(array_map('strtolower', $selectDays), array_map('strtolower', explode('/', $relatedClassList[$x]->days)))) > 0 ){ // Have same days with a db array
                    if($relatedClassList[$x]->days!='None' && $relatedClassList[$x]->start_time!='None' && $relatedClassList[$x]->end_time!='None'){
                        $db_start_time = Carbon::parse($relatedClassList[$x]->start_time);
                        $db_end_time = Carbon::parse($relatedClassList[$x]->end_time);
                        // print_r($relatedClassList->count());
                        for($c=$db_start_time;$c<$db_end_time;$c->modify('+1 minutes')){
                            $dbTimeArray[] = $c->format('h:i a');
                        }
                    }
                }
            }
            $gettersArray = array_intersect($dbTimeArray, $requestTimeArray);
            if(count($gettersArray)>0){ // There is an existing schedule class clashing
                return ResponseHelper::response_error('Schedule is clashing with an existing class. Please reschedule class.', []);
            }
        }

        $data = $this->classService->editClass($id, $payload);
        return ResponseHelper::response_success('Class Schedule Updated', $data);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassList  $class
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassList $class)
    {
        //
    }
}
