<?php

namespace App\Transformers;
use App\Models\EducationInfo;
use League\Fractal\TransformerAbstract;

class EducationInfoTransformer extends TransformerAbstract
{
    public function transform(EducationInfo $educationInfo)
    {
        return [
            'id' => $educationInfo->id,
            'user_id' => $educationInfo->user_id,
            'school' => $educationInfo->school,
            'major' => $educationInfo->major,
            'degree' => $educationInfo->degree,
            'edu_description' => $educationInfo->edu_description ?? '',
            'start_year' => $educationInfo->start_year,
            'end_year' => $educationInfo->end_year
        ];
    }
}