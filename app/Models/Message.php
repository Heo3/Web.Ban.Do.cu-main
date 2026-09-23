<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    protected $appends = [
        'formatted_time',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Định dạng thời gian gửi tin nhắn thân thiện
     */
    public function getFormattedTimeAttribute(): string
    {
        if (!$this->created_at) {
            return '';
        }

        if ($this->created_at->isToday()) {
            return $this->created_at->format('H:i');
        }

        if ($this->created_at->isYesterday()) {
            return 'Hôm qua ' . $this->created_at->format('H:i');
        }

        return $this->created_at->format('H:i d/m');
    }
}
