<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SubjectService;
use App\Helpers\ResponseHelper;
use App\Models\Subject;

class SubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index(Request $request)
    {
        $data = $this->subjectService->findAll($request);

        return ResponseHelper::response_success('Subjects', $data);
    }

    public function store(Request $request){

        $payload = $request->validate([
            'code' => 'required',
            'title' => 'required',
            'units' => 'required',
            'grade_level' => 'required',
        ]);
        $record = Subject::where(function ($query) use ($request){
            $query->where('title', 'like', '%' . preg_quote($request->title) . '%')
            ->orWhere('code', 'like', '%' . preg_quote($request->code) . '%');
        })->count();
        
        if($record > 0){
            return ResponseHelper::response_error('Subject title or code already exists.', []);
        } 

        $data = $this->subjectService->createSubject($payload);

        return ResponseHelper::response_created('Subject Created', $data);

    }

    public function edit(Request $request, $id)
    {
        $payload = $request->validate([
            'code' => 'required',
            'title' => 'required',
            'units' => 'required',
            'grade_level' => 'required',
        ]);

        $data = $this->subjectService->editSubject($id, $payload);

        return ResponseHelper::response_success('Subject Updated', $data);
    }

    public function destroy(Request $request, $id)
    {
        //
    }

    public function validateData($request)
    {
        return $request->validate([
            'code' => 'required',
            'title' => 'required',
            'units' => 'required',
            'grade_level' => 'required',
        ]);
    }
}
