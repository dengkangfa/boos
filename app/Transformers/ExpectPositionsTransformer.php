<?php

namespace App\Transformers;

use App\Models\ExpectPosition;
use League\Fractal\TransformerAbstract;

class ExpectPositionsTransformer extends TransformerAbstract
{
    public function transform(ExpectPosition $expectPosition)
    {
        return [
            'id' => $expectPosition->id,
            'apply_status' => (int) $expectPosition->apply_status,
            'position_type' => $expectPosition->position_type,
            'industry' => $expectPosition->industry,
            'location_name' => $expectPosition->location_name,
            'low_salary' => (int) $expectPosition->low_salary,
            'high_salary' => (int) $expectPosition->high_salary
        ];
    }
}