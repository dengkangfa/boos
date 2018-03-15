<?php

namespace App\Http\Controllers\Api;

use App\Models\ExpectPosition;
use App\Transformers\ExpectPositionsTransformer;
use Illuminate\Http\Request;
use Validator;

class ExpectPositionController extends ApiController
{
    public function store(Request $request, ExpectPosition $expectPosition)
    {
        $validator = $this->validateExpectPosition($request);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        $expectPosition->fill($request->all());
        $expectPosition->user_id = \Auth::id();
        $expectPosition->save();

        return $this->respondWithItem($expectPosition, new ExpectPositionsTransformer());
    }

    public function validateExpectPosition(Request $request)
    {
        return Validator::make($request->all(), [
            'apply_status'     => 'required|numeric|in:0,1,2,3',
            'position_type'      => 'required|exists:position_types,id',
            'location_name'     => 'required',
        ], [
            'apply_status.required' => '请填写学校名称',
            'position_type.required' => '请选择期待职位',
            'position_type.exists' => '该职位不存在',
        ], [
            'apply_status' => '求职状态',
            'position_type' => '期待职位',
            'location_name' => '工作城市'
        ]);
    }
}
