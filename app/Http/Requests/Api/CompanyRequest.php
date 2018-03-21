<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|between:5,46',
            'abbreviation' => ['required','between:1,10',
                Rule::unique('companies', 'abbreviation')->where(function ($query) {
                    $query->where('status', '1');
                })],
            'industry_str' => 'required|exists:industry,name',
            'industry_code' => 'required|exists:industry,code',
            'people' => 'required|in:0,1,2,3,4,5'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '公司全称',
            'abbreviation' => '公司简称',
            'industry_str' => '公司行业名称',
            'industry_code' => '公司行业 code',
            'people' => '公司规模'
        ];
    }

    public function messages()
    {
        return [
            'abbreviation.unique' => '公司简称已经存在'
        ];
    }
}
