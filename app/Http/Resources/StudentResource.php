<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   
        return [
            'id'            => $this->id,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'email'         => $this->email,
            'learners'      => $this->learners_number,
            'mobile'        => $this->mobile,
            'birth_date'    => $this->birth_date,
            'address'       => $this->address,
            'contact_name'  => $this->contact_name,
            'contact_number'=> $this->contact_number,
            'contact_relationship'=> $this->contact_relationship,
            'role'          => $this->role,
            'admission_type'=> $this->admission_type,
            'address'       => $this->address,
            'section'       => $this->section
        ];
    }
}
