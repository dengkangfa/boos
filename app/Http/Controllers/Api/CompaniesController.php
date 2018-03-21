<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CompanyRequest;
use App\Models\Company;
use App\Transformers\CompanyTransformer;
use Illuminate\Http\Request;
use League\Fractal;

class CompaniesController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['searchWithLike', 'search']]);

        parent::__construct();
    }

    public function store(CompanyRequest $request, Company $company)
    {
        $company->fill($request->all());
        $company->save();
        $company->boss()->save(\Auth()->user());

        return $this->setStatusCode(201)->respondWithItem($company, new CompanyTransformer());
    }

    public function searchWithLike(Request $request, Company $company)
    {
        $companies = $company->where($request->field, 'like', $request->wk.'%')->take($request->limit ?? 20)->get();

        return $this->respondWithCollection($companies, new CompanyTransformer());
    }

    public function search(Request $request, Company $company)
    {
        $query = array_only($request->query(), $company->getFillable());
        $companies = $company->where($query)->get();

        return $this->respondWithCollection($companies, new CompanyTransformer());
    }
}
