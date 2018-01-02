<?php

namespace App\Http\Controllers\Api;

use App\Transformers\EducationInfoTransformer;
use Auth;
use App\Repositories\EducationInfoRepository;
use Validator;
use App\Http\Requests\EducationInfoRequest;
use Illuminate\Http\Request;

class EducationInfoController extends ApiController
{
    /**
     * @var EducationInfoRepository
     */
    private $educationInfo;

    public function __construct(EducationInfoRepository $educationInfo)
    {
        parent::__construct();
        $this->educationInfo = $educationInfo;
    }

    public function index()
    {
        $educationInfos = Auth::user()->educationInfos()->first();
        return $this->respondWithItem($educationInfos, new EducationInfoTransformer);
    }

    public function store(Request $request)
    {
        $validator = $this->validateEducationInfo($request);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $educationInfo = $this->educationInfo->store($data);
        return response()->json([
            'success' => true,
            'message' => 'Education Info Created',
            'data' => $educationInfo,
            'code' => 0
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateEducationInfo($request);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        return $this->educationInfo->update($id, $request->only('school', 'major', 'degree', 'edu_description', 'start_year', 'end_year'));
    }

    public function validateEducationInfo(Request $request)
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