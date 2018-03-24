<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\JobRequest;
use App\Models\Company;
use App\Models\Job;
use App\Transformers\JobTransformer;

class JobController extends ApiController
{
    public function store(JobRequest $request, Company $company, Job $job)
    {
        $job->fill($request->all());
        $job->user_id = \Auth::id();
        $job->company_id = $company->id;
        $job->save();

        return $this->setStatusCode(201)->respondWithItem($job, new JobTransformer());
    }
}
