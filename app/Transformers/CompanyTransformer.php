<?php


namespace App\Transformers;


use App\Models\Company;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];

    private $validParams = ['limit'];

    public function transform(Company $company)
    {
        return [
            'id' => $company->id,
            'name' => $company->name,
            'pic' => $company->pic,
            'abbreviation' => $company->abbreviation,
            'industry_str' => $company->industry_str,
            'industry_code' => $company->industry_code,
            'stock' => $company->stock,
            'people' => (int) $company->people,
            'detial' => $company->detial,
            'boss_count' => $company->boss()->count(),
            'created_at' => $company->created_at->toDateTimeString(),
            'updated_at' => $company->updated_at->toDateTimeString()
        ];
    }

    public function includeUser(Company $company, ParamBag $params = null)
    {
        if ($params === null) {
            return $company->boss;
        }

        // Optional params validation
        $usedParams = array_keys(iterator_to_array($params));
        if ($invalidParams = array_diff($usedParams, $this->validParams)) {
            throw new \Exception(sprintf(
                'Invalid param(s): "%s". Valid param(s): "%s"',
                implode(',', $usedParams),
                implode(',', $this->validParams)
            ));
        }

        // Processing
        list($limit, $offset) = $params->get('limit');

        $boss = $company->boss()
            ->take($limit)
            ->skip($offset)
            ->get();

        return $this->collection($boss, new UserTransformer);
    }
}