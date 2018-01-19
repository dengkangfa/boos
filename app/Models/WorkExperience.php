<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    protected $fillable = ['user_id', 'company_name', 'start_time', 'end_time', 'position_type', 'work_emphasis', 'responsibility'];

    protected $dates = ['created_at', 'updated_at', 'start_time', 'end_time'];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function positionType()
    {
        $this->belongsTo(PositionType::class);
    }

    public function setEndTimeAttribute($value)
    {
        if ($value === -1) {
            $this->attributes['end_time'] = null;
        } else {
            $this->attributes['end_time'] = Carbon::createFromTimeStamp(strtotime($value));
        }
    }

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = Carbon::createFromTimestamp(strtotime($value));
    }
}
