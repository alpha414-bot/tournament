<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomBadRequestException;
use App\Helpers\ResponseHelper;
use App\Models\Section;
use App\Services\SectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    public function __construct(SectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $data = $this->sectionService->findAll($request);

        return ResponseHelper::response_success('Sections', $data);
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
            'grade_level_id' => 'required',
            'section' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponseHelper::response_error('Create Section Failed', $validator->messages());
        }
        $record = Section::where('section', 'like', '%' . preg_quote($request->section) . '%')->count();
        
        if($record > 0){
            return ResponseHelper::response_error('Section name already exists.', []);
        } 
        $data = $this->sectionService->addSection($request->all());

        return ResponseHelper::response_created('Section created', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $validator = Validator::make($request->all(),[
            'section' => 'required'
        ]);

        if($validator->fails()){
            return ResponseHelper::response_error('Update Section Failed', $validator->messages());
        }

        $data = $this->sectionService->updateSection($request->all(),$id);

        return ResponseHelper::response_created('Section created', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$id){
            throw new CustomBadRequestException('Invalid Section ID');
        }

        try{
            $section =  Section::where('id', $id)->firstOrFail();
            $section->delete();
            
            return ResponseHelper::response_success('Subject removed successfully', []);
        } catch(\Throwable $e){
            throw new CustomBadRequestException('Assign Subjects Failed');
        }
    
    }
}
