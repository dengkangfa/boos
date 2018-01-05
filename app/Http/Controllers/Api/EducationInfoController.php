<?php

namespace App\Http\Controllers\Api;

use App\Transformers\EducationInfoTransformer;
use Auth;
use App\Repositories\EducationInfoRepository;
use Validator;
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

        $this->middleware('auth');

        $this->educationInfo = $educationInfo;
    }

    public function index()
    {
        $educationInfos = Auth::user()->educationInfos()->first();

        if (is_null($educationInfos)) {
            return $this->noContent();
        }
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
        return $this->respondWithItem($educationInfo, new EducationInfoTransformer);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateEducationInfo($request);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        $educationInfo = $this->educationInfo->update($id, $request->only('school', 'major', 'degree', 'edu_description', 'start_year', 'end_year'));
        return $this->respondWithItem($educationInfo, new EducationInfoTransformer);
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