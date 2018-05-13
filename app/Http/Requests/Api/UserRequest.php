<?php

namespace App\Http\Requests\Api;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:12',
            'gender' => 'in:male,female',
            'birth_date' => 'date',
            'job_date' => 'date',
            'company_id' => 'exists:companies,id',
            'pos_name' => 'max:12',
            'email' => 'email',
            'job_status' => 'numeric|in:0,1,2,3',
        ];
    }
}
