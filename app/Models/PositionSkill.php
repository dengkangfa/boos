<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionSkill extends Model
{
    protected $fillable = ['name', 'positonLv2'];

    public function scopeByPositionLv2($query, $positionLv2)
    {
        return $query->where('positonLv2', '=', $positionLv2);
    }
}
