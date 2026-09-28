<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
