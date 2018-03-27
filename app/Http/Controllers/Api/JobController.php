<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\JobRequest;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Job;
use App\Transformers\JobTransformer;

class JobController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
        parent::__construct();
    }

    public function index(Request $request, Job $job)
    {
        $user = \Auth::user();
        $expectPosition = $user->expectPosition;
        $query = $job->query();

        if ($type_code = $expectPosition->position_type) {
            $query->where('type_code', $type_code);
        }

        $jobs = $query->take($request->page)->limit($request->prepage)->paginate(10);

        return $this->respondWithPaginator($jobs, new JobTransformer(true));
    }

    public function store(JobRequest $request, Company $company, Job $job)
    {
        $job->fill($request->all());
        $job->user_id = \Auth::id();
        $job->company_id = $company->id;
        $job->save();

        return $this->setStatusCode(201)->respondWithItem($job, new JobTransformer());
    }

    public function show(Job $job)
    {
        return $this->respondWithItem($job, new JobTransformer());
    }
}
