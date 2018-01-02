<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationInfo extends Model
{
    protected $fillable = ['user_id', 'school', 'major', 'degree', 'edu_description', 'start_year', 'end_year'];
}
