<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProjectExperience extends Model
{
    protected $fillable = ['name', 'url', 'role', 'description', 'performance', 'start_time', 'end_time', 'user_id'];

    protected $dates = ['created_at', 'updated_at', 'start_time', 'end_time'];

    public function user()
    {
        $this->belongsTo(User::class);
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
