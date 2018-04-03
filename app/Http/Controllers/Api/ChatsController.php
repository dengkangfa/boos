<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatMessageWasReceived;
use App\Events\newContacts;
use App\Http\Controllers\Api\ApiController;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Contact;
use App\Models\User;
use App\Transformers\ChatMessageTransformer;
use App\Transformers\ChatTransformer;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class ChatsController extends ApiController
{
    public function store(Request $request, Chat $chat)
    {
        $user = \Auth::user();
        if (!$contact = $user->contacts()->where('contact_id', $request->user_id)->first()) {
            $uuid = Uuid::uuid1()->getHex();
            $chat->uuid = $uuid;
            $chat->job_id = $request->job_id;
            $chat->save();

            $user->contacts()->attach($request->user_id, ['chat_uuid' => $uuid]);
            return $this->setStatusCode(201)->respondWithItem($chat, new ChatTransformer());
        } elseif ($contact) {
            $chat = $chat->where('uuid', $contact->pivot->chat_uuid)->first();
            if ($contact->job_id !== $request->job_id) {
                return $this->setCode(10054)->respondWithItem($chat, new ChatTransformer());
            }
            return $this->respondWithItem($chat, new ChatTransformer());
        }
    }

    public function show($uuid, Chat $chat)
    {
        $chat = $chat::where('uuid', $uuid)->first();
        if (!$chat) {
            return $this->errorNotFound();
        }
        $chat->messages()->where('user_id', '!=', \Auth::id())->update(['read_at' => Carbon::now()]);

        return $this->respondWithItem($chat, new ChatTransformer());
    }

    public function storeMessages(Request $request, $uuid, ChatMessage $message)
    {
        $message->message = $request->message;
        $message->chat_uuid = $uuid;
        $message->user_id = \Auth::id();
        $message->save();

        if (Contact::where('chat_uuid', $uuid)->count() === 1) {
            $contact = Contact::where('chat_uuid', $uuid)->first();
            $user = User::find($contact->contact_id);
            $user->contacts()->attach(\Auth::id(), ['chat_uuid' => $uuid]);
            event(new NewContacts($user, \Auth::user(), $message->chat_uuid));
        }
        $contact = Contact::where('chat_uuid', $uuid)->where('user_id', \Auth::id())->first();
        broadcast(new ChatMessageWasReceived($message, \Auth::user(), $contact->contact_id))->toOthers();
        return $this->setStatusCode(201)->respondWithItem($message, new ChatMessageTransformer());
    }
}
