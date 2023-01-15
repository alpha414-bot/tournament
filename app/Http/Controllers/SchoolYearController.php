<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\SchoolYear;
use App\Services\SchoolYearService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $schoolYearService;
    public function __construct(SchoolYearService $schoolYearService){
        $this->schoolYearService = $schoolYearService;
    }
    
    public function index()
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
        //
        $validator = Validator::make($request->all(), [
            'school_year'=>'required'
        ]);
        if ($validator->fails()) {
            return ResponseHelper::response_error('School Year creation failed.', $validator->messages());
        }
        $record = SchoolYear::where('school_year', $request->school_year)->get()->count();
        if($record>0){
            return ResponseHelper::response_error('School Year already exists.', []);
        }
        $data = $this->schoolYearService->createSchoolYear($request->all());

        return ResponseHelper::response_created('School Year Created.', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolYear $schoolYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function edit(SchoolYear $schoolYear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = $request->validate([
            'school_year'=>'required'
        ]);
        $record = SchoolYear::where('school_year', $request->school_year)->get()->count();
        if($record>0){
            return ResponseHelper::response_error('School Year already exists.', []);
        }
        $data = $this->schoolYearService->updateSchoolYear($id, $validator);
        return ResponseHelper::response_success('School Year updated.', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $record = SchoolYear::where('id', $id)->get()->count();
        if($record==0){
            return ResponseHelper::response_error("School Year doesn't exists.", []);
        }
        SchoolYear::find($id)->delete();
        return ResponseHelper::response_success('School Year deleted', []);
    }
}
