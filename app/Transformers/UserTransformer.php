<?php

namespace App\Transformers;

use App\Models\ChatMessage;
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
            $result = [
                'id'         => $user->id,
                'avatar'     => $user->avatar,
                'name'       => $user->name,
                'pos_name'   => $user->pos_name
            ];
            if (isset($user->pivot)) {
                $result['chat_uuid'] = $user->pivot->chat_uuid;
                $query = [
                    'chat_uuid' => $user->pivot->chat_uuid,
                    'user_id' => $user->id,
                    'read_at' => null
                ];
                $result['new_message_count'] = ChatMessage::where($query)->count();
                $result['last_message'] = ChatMessage::where('chat_uuid', $user->pivot->chat_uuid)->orderBy('created_at', 'desc')->select('user_id', 'message', 'read_at', 'created_at')->first();
            }
            return $result;
        } else {
            return [
                'id'         => $user->id,
                'avatar'     => $user->avatar,
                'email'      => $user->email,
                'name'       => $user->name,
                'mobile'     => $user->mobile,
                'gender'     => $user->gender,
                'wechat'     => $user->wechat,
                'advantage'  => $user->advantage ?? '',
                'company_id' => $user->company_id,
                'pos_name'   => $user->pos_name,
                'job_date'   => $user->job_date ? ($user->job_date->format('Y-m') !== '1989-11' ? $user->job_date->format('Y-m') : '1990年以前') : '应届生',
                'birth_date' => $user->birth_date ? $user->birth_date->format('Y-m') : '',
                'homepages'  => $user->homepages ? array_slice(explode('・', $user->homepages), 0, 3) : [],
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