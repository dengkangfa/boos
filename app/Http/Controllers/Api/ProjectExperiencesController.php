<?php

namespace App\Http\Controllers\Api;

use App\Models\ProjectExperience;
use Auth;
use Validator;
use App\Repositories\ProjectExperienceRepository;
use App\Transformers\ProjectExperienceTransformer;
use Illuminate\Http\Request;

class ProjectExperiencesController extends ApiController
{
    protected $projectExperience;

    public function __construct(ProjectExperienceRepository $projectExperience)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->projectExperience = $projectExperience;
    }

    public function store(Request $request)
    {
        $validator = $this->validateProjectExperience($request);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $projectExperience = $this->projectExperience->store($data);
        return $this->respondWithItem($projectExperience, new ProjectExperienceTransformer());
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateProjectExperience($request);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        $workExperience = $this->projectExperience->update($id, $request->all());
        return $this->respondWithItem($workExperience, new ProjectExperienceTransformer);
    }

    public function destroy(ProjectExperience $projectExperience)
    {
        $this->authorize('destroy', $projectExperience);
        $projectExperience->delete();

        return $this->noContent();
    }

    public function currentUserAllProjectExperience(Request $request)
    {
        $projectExperiences = Auth::user()->projectExperiences->sortByDesc('created_at');

        if (!count($projectExperiences)) {
            return $this->noContent();
        }
        return $this->respondWithCollection($projectExperiences, new ProjectExperienceTransformer);
    }

    public function validateProjectExperience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'url' => 'nullable|regex:/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/',
            'role' => 'required|string',
            'description' => 'required|string|max:1600',
            'performance' => 'nullable|string|max:300',
            'start_time'   => 'required|date_format:Y-m',
            'end_time'   => 'required'
        ], [
            'name.required' => '请填写项目名称',
            'description.max' => '项目描述字数不得超过:max',
            'url.url' => '请输入合法的网站地址',
            'role.required' => '请填写项目角色',
            'role.required' => '请填写项目角色',
            'start_time.required' => '请选择时间段',
            'end_time.required' => '请选择时间段'
        ]);

        return $validator;
    }
}
