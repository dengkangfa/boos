<?php

namespace App\Transformers;

use App\Models\projectExperience;
use League\Fractal\TransformerAbstract;

class ProjectExperienceTransformer extends TransformerAbstract
{
    public function transform(projectExperience $projectExperience)
    {
        return [
            'id' => $projectExperience->id,
            'name' => $projectExperience->name,
            'url' => $projectExperience->url,
            'role' => $projectExperience->role,
            'description' => $projectExperience->description,
            'performance' => $projectExperience->performance,
            'start_time' => $projectExperience->start_time->format('Y-m'),
            'end_time' => is_null($projectExperience->end_time) ? -1 : $projectExperience->end_time->format('Y-m'),
        ];
    }
}