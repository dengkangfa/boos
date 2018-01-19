<?php

namespace App\Http\Controllers\Api;

use App\Transformers\WorkExperienceTransformer;
use Auth;
use App\Repositories\WorkExperienceRepository;
use Illuminate\Http\Request;
use Validator;

class WorkExperienceController extends ApiController
{
    protected $workExperience;

    public function __construct(WorkExperienceRepository $workExperience)
    {
        parent::__construct();

        $this->workExperience = $workExperience;
    }

    public function me()
    {
        $workExperiences = Auth::user()->workExperiences()->first();

        if (is_null($workExperiences)) {
            return $this->noContent();
        }
        return $this->respondWithItem($workExperiences, new WorkExperienceTransformer);
    }

    public function store(Request $request)
    {
        $validator = $this->validateWorkExperience($request);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $workExperience = $this->workExperience->store($data);
        return $this->respondWithItem($workExperience, new WorkExperienceTransformer);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateWorkExperience($request);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        $workExperience = $this->workExperience->update($id, $request->all());
        return $this->respondWithItem($workExperience, new WorkExperienceTransformer);
    }

    public function validateWorkExperience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'start_time'   => 'required|date_format:Y-m',
            'end_time'   => 'required',
            'position_type' => 'required',
            'work_emphasis' => 'required',
            'responsibility' => 'required|max:1600'
        ], [
            'company_name.required' => '请填写公司名称',
            'start_time.required' => '请选择时间段',
            'end_time.required' => '请选择时间段',
            'position_type.required' => '请填写职位类型',
            'work_emphasis.required' => '请选择技能标签',
            'responsibility.required' => '工作内容的描述很重要，请务必填写。',
            'responsibility.max' => '工作内容字数不得超过:max'
        ]);

        return $validator;
    }
}
