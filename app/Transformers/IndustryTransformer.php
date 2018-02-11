<?php

namespace App\Transformers;

use App\Models\Industry;
use League\Fractal\TransformerAbstract;

class IndustryTransformer extends TransformerAbstract
{
    public function transform(Industry $industry)
    {
        return [
            'code' => $industry->code,
            'name' => $industry->name,
            'subLevelModelList' => $industry->subLevelModelList ?? null
        ];
    }
}