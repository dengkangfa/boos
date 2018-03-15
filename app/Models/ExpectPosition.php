<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpectPosition extends Model
{
    protected $fillable = ['apply_status', 'position_type', 'industry', 'location_name', 'low_salary', 'high_salary', 'user_id'];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function positionType()
    {
        $this->belongsTo(PositionType::class, 'position_type');
    }
}
