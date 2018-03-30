<?php

namespace App\Transformers;

use App\Models\ChatMessage;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class ChatMessageTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];

    protected $simple;

    public function __construct($simple = false)
    {
        $this->simple = $simple;
    }

    public function transform(ChatMessage $chatMessage)
    {
        return [
            'type' => $chatMessage->type,
            'message' => $chatMessage->message,
            'read_at' => $chatMessage->read_at,
            'created_at' => $chatMessage->created_at->diffForHumans()
        ];
    }

    public function includeUser(ChatMessage $chatMessage)
    {
        $user = $chatMessage->user;

        return $this->item($user, new UserTransformer($this->simple));
    }
}