<?php

namespace App\Transformers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Contact;
use App\Models\Job;
use App\Models\User;
use App\Transformers\ChatMessageTransformer;
use League\Fractal\TransformerAbstract;

class ChatTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['job', 'message', 'contact'];
    
    public function transform(Chat $chat)
    {
        return [
            'uuid' => $chat->uuid,
            'job_id' => $chat->job_id,
        ];
    }

    public function includeMessage(Chat $chat)
    {
        $messages = $chat->messages;

        return $this->collection($messages, new ChatMessageTransformer());
    }

    public function includeJob(Chat $chat)
    {
        $job = $chat->job;

        return $this->item($job, new JobTransformer());
    }

    public function includeContact(Chat $chat)
    {
        $rule['user_id'] = \Auth::id();
        $rule['chat_uuid'] = $chat->uuid;
        $contact = Contact::where($rule)->first();

        return $this->item(User::find($contact->contact_id), new UserTransformer());
    }
}