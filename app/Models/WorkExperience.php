<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    protected $fillable = ['company_name', 'start_time', 'end_time', 'position_type', 'work_emphasis', 'responsibility'];
}
