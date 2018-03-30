<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Console\Command;

class SendChatMessage extends Command
{
    protected $signature = 'chat:message {message}';

    protected $description = 'Send chat message.';

    public function handle()
    {
        // Fire off an event, just randomly grabbing the first user for now
        $user = User::first();
        $message = ChatMessage::create([
            'user_id' => $user->id,
            'message' => $this->argument('message')
        ]);

        event(new \App\Events\ChatMessageWasReceived($message, $user));
    }
}
