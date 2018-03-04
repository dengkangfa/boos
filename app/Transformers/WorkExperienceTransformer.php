<?php

namespace App\Transformers;

use App\Models\WorkExperience;
use League\Fractal\TransformerAbstract;

class WorkExperienceTransformer extends TransformerAbstract
{
    public function transform(WorkExperience $workExperience)
    {
        return [
            'id' => $workExperience->id,
            'company_name' => $workExperience->company_name,
            'start_time' => $workExperience->start_time->format('Y-m'),
            'end_time' => is_null($workExperience->end_time) ? -1 : $workExperience->end_time->format('Y-m'),
            'position_type' => $workExperience->position_type,
            'position_name' => $workExperience->position_name,
            'industry' => $workExperience->industry,
            'work_emphasis' => $workExperience->work_emphasis,
            'subordinate' => (string)$workExperience->subordinate,
            'responsibility' => $workExperience->responsibility,
            'performance' => (string)$workExperience->performance,
            'veiled' => (bool)$workExperience->veiled
        ];
    }
}