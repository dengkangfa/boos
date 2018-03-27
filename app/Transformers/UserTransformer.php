<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['company'];

    private $simple;

    public function __construct($simple = false)
    {
        $this->simple = $simple;
    }

    public function transform(User $user)
    {
        if ($this->simple) {
            return [
                'avatar'     => $user->avatar,
                'name'       => $user->name,
                'pos_name'   => $user->pos_name
            ];
        } else {
            return [
                'id'         => $user->id,
                'avatar'     => $user->avatar,
                'email'      => $user->email,
                'name'       => $user->name,
                'mobile'     => $user->mobile,
                'gender'     => $user->gender,
                'advantage'  => $user->advantage ?? '',
                'company_id' => $user->company_id,
                'pos_name'   => $user->pos_name,
                'job_date'   => $user->job_date ? ($user->job_date->format('Y-m') !== '1989-11' ? $user->job_date->format('Y-m') : '1990年以前') : '应届生',
                'birth_date' => $user->birth_date ? $user->birth_date->format('Y-m') : '',
                'homepages'  => array_slice(explode('・', $user->homepages), 0, 3),
                'roles' => $user->getRoleNames(),
                'last_actived_at' => $user->last_actived_at->diffForHumans(),
                'created_at' => $user->created_at->toDateTimeString(),
                'updated_at' => $user->updated_at->toDateTimeString()
            ];
        }
    }

    public function includeCompany(User $user)
    {
        $company = $user->company;

        return $this->item($company, new CompanyTransformer());
    }
}