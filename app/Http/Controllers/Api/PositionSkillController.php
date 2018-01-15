<?php

namespace App\Http\Controllers\Api;

use App\Models\PositionSkill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PositionSkillController extends Controller
{
    public function index(Request $request)
    {
        if ($request->positonLv2) {
            return PositionSkill::byPositionLv2($request->positonLv2)->get()->pluck('name')->toArray();
        }
        return PositionSkill::all()->toArray();
    }
}
