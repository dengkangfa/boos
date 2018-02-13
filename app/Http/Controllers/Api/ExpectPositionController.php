<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Validator;

class ExpectPositionController extends ApiController
{
    public function store(Request $request)
    {
        $validator = $this->validateExpectPosition($request);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $educationInfo = $this->educationInfo->store($data);
        return $this->respondWithItem($educationInfo, new EducationInfoTransformer);
    }

    public function validateExpectPosition(Request $request)
    {
        return Validator::make($request->all(), [
            'school'     => 'required',
            'major'      => 'required',
            'degree'     => 'required',
            'start_year' => 'required|date_format:Y',
            'end_year'   => 'required|date_format:Y|after:start_year'
        ], [
            'school.required' => '请填写学校名称',
            'major.required' => '请填写所学专业',
            'degree.required' => '请选择学历',
            'start_year.required' => '请选择在校时间段',
            'end_year.required' => '请选择在校时间段'
        ]);
    }
}
