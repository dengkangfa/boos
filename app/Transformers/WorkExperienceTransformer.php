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
            'work_emphasis' => $workExperience->work_emphasis,
            'responsibility' => $workExperience->responsibility
        ];
    }
}