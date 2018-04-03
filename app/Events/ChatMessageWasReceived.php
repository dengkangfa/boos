<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChatMessageWasReceived implements ShouldBroadcast
{
    use SerializesModels;

    public $chatMessage;

    public $user;

    protected $user_id;

    public function __construct($chatMessage, $user, $user_id)
    {
        $this->chatMessage = $chatMessage;
        $this->user = $user;
        $this->user_id = $user_id;
    }

//    public function broadcastAs()
//    {
//        return 'ChatMessageWasReceived';
//    }

    public function broadcastOn()
    {
        return [
            'user.'.$this->user_id,
            'chat-room.'.$this->chatMessage->chat_uuid
        ];
    }

}