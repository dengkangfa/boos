<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id'         => $user->id,
            'avatar'     => $user->avatar,
            'name'       => $user->name,
            'mobile'     => $user->mobile,
            'gender'     => $user->gender,
            'advantage'  => $user->advantage,
            'job_date'   => $user->job_date ? ($user->job_date->format('Y-m') !== '1989-11' ? $user->job_date->format('Y-m') : '1990年以前') : '应届生',
            'birth_date' => $user->birth_date->format('Y-m')
        ];
    }
}