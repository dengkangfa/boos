<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['user_id', 'contact_id', 'job_id', 'chat_uuid'];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'uuid', 'contact_uuid');
    }
}
