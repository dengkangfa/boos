<?php

namespace App\Http\Requests\Api;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:12',
            'gender' => 'in:male,female',
            'job_date' => 'date',
            'birth_date' => 'date',
            'company_id' => 'exists:companies,id',
            'pos_name' => 'max:12',
            'email' => 'email'
        ];
    }
}
