<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['uuid', 'job_id', 'last_message'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_uuid', 'uuid');
    }
}
