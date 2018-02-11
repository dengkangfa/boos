<?php

namespace App\Http\Controllers\Api;

use App\Models\Industry;
use App\Transformers\IndustryTransformer;
use Illuminate\Http\Request;

class IndustryController extends ApiController
{
    public function index()
    {
        return $this->format(Industry::all());
    }

    public function format($industry)
    {
        $data = [];
        foreach ($industry as $item) {
            if ($item['parent_code']) {
                $data[$item['parent_code']]['subLevelModelList'][$item['id']] = $item;
            } else {
                $data[$item['code']] = $item->toArray();
                $data[$item['code']]['subLevelModelList'] = [];
            }
        }
        return $data;
    }
}
