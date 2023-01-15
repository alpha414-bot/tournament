<?php
namespace App\Services;

use App\Models\SchoolYear;
use App\Exceptions\CustomBadRequestException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class SchoolYearService
{
    protected $model;
    public function __construct(SchoolYear $school_year){
        $this->model = $school_year;
    }

    public function createSchoolYear($data){
        if(!$data){
            throw new CustomBadRequestException('School Year Creation failed');
        }

        try{
            return SchoolYear::create([
                'school_year'=>$data['school_year']
            ]);
        } 
        catch(\Throwable $e){
            throw new CustomBadRequestException("School Year Creation failed {$e}");
        }
    }

    public function updateSchoolYear($id, $data){
        if(!$data){
            throw new CustomBadRequestException('Field needs to be present');
        }

        $yearModule = SchoolYear::where('id', $id)->first();
        if($yearModule==null){
            throw new CustomBadRequestException("School year doesn't exist");
        }
        $yearModule->school_year = $data['school_year'];
        $yearModule->save();
        return $yearModule->fresh();
    } 
}