<?php

namespace App\Http\Requests\Api;

class JobRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type_str' => 'required|exists:position_types,name',
            'type_code' => 'required|exists:position_types,id',
            'name' => 'required|string|max:20',
            'province' => 'required|string',
            'city' => 'required|string',
            'area' => 'required|string',
            'street' => 'required|string',
            'nature' => 'required|in:全职,兼职,实习',
            'work_emphasis' => 'required|max:60',
            'low_salary' => 'required|numeric',
            'high_salary' => 'required|numeric',
            'seniority' => 'required|string',
            'education' => 'required|string|in:不限,中专及以下,高中,大专,本科,硕士,博士',
            'description' => 'required|string'
        ];
    }

    public function attributes()
    {
        return [
            'type_str' => '职位类型名称',
            'type_code' => '职位类型 code',
            'name' => '职位名称',
            'place' => '工作地点',
            'nature' => '工作性质',
            'work_emphasis' => '技能标签',
            'low_salary' => '薪资范围',
            'high_salary' => '薪资范围',
            'seniority' => '经验要求',
            'education' => '最低学历',
            'description' => '职位描述'
        ];
    }
}
