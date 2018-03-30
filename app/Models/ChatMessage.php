<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    public $fillable = ['user_id', 'message', 'type', 'read_at', 'created_at'];

    protected $dates = ['created_at', 'updated_at', 'read_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
