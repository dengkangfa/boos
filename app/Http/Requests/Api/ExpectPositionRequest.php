<?php

namespace App\Http\Requests\Api;

class ExpectPositionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'apply_status'     => 'required|numeric|in:0,1,2,3',
            'position_type'      => 'required|exists:position_types,id',
            'location_name'     => 'required',
            'low_salary' => 'required|numeric',
            'high_salary' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'apply_status' => '求职状态',
            'position_type' => '期待职位',
            'location_name' => '工作城市',
            'low_salary' => '薪资要求',
            'high_salary' => '薪资要求'
        ];
    }
}
