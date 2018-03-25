<?php

namespace App\Transformers;

use App\Models\Job;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class JobTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'company'];

    public function transform(Job $job)
    {
        return [
            'user_id' => $job->user_id,
            'company_id' => $job->company_id,
            'type_str' => $job->type_str,
            'type_code' => $job->type_code,
            'name' => $job->name,
            'place' => $job->place,
            'nature' => $job->nature,
            'work_emphasis' => array_slice(explode('・', $job->work_emphasis), 0, 3),
            'low_salary' => (int) $job->low_salary,
            'high_salary' => (int) $job->high_salary,
            'seniority' => $job->seniority,
            'education' => $job->education,
            'description' => $job->description,
            'created_at' => $job->created_at->toDateTimeString(),
            'updated_at' => $job->updated_at->toDateTimeString()
        ];
    }

    public function includeUser(Job $job)
    {
        $user = $job->user;

        return $this->item($user, new UserTransformer());
    }

    public function includeCompany(Job $job)
    {
        $company = $job->company;

        return $this->item($company, new CompanyTransformer());
    }
}