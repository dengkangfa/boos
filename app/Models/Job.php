<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'user_id', 'company_id', 'type_str', 'type_code', 'name', 'place', 'nature',
        'work_emphasis', 'low_salary', 'high_salary', 'seniority', 'education', 'description'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
