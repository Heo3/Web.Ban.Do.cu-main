<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'product_id',
        'last_message',
        'last_message_at',
        'buyer_unread_count',
        'seller_unread_count',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'buyer_unread_count' => 'integer',
        'seller_unread_count' => 'integer',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Lấy đối tượng người chat còn lại (không phải user hiện tại)
     */
    public function getOtherUser($currentUserId)
    {
        if ($this->buyer_id == $currentUserId) {
            return $this->seller;
        }
        return $this->buyer;
    }

    /**
     * Lấy số lượng tin nhắn chưa đọc của user hiện tại trong hội thoại này
     */
    public function getUnreadCount($currentUserId): int
    {
        if ($this->buyer_id == $currentUserId) {
            return (int) $this->buyer_unread_count;
        }
        return (int) $this->seller_unread_count;
    }

    /**
     * Đặt lại số tin nhắn chưa đọc của user hiện tại về 0
     */
    public function markAsReadFor($currentUserId): void
    {
        if ($this->buyer_id == $currentUserId) {
            if ($this->buyer_unread_count > 0) {
                $this->update(['buyer_unread_count' => 0]);
            }
        } else {
            if ($this->seller_unread_count > 0) {
                $this->update(['seller_unread_count' => 0]);
            }
        }

        // Đánh dấu các tin nhắn được gửi tới user này là đã đọc
        $this->messages()
            ->where('sender_id', '!=', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Tăng số tin chưa đọc cho người nhận khi người gửi gửi tin nhắn mới
     */
    public function recordNewMessage($senderId, string $messageText): void
    {
        $data = [
            'last_message' => $messageText,
            'last_message_at' => now(),
        ];

        if ($senderId == $this->buyer_id) {
            $data['seller_unread_count'] = $this->seller_unread_count + 1;
        } else {
            $data['buyer_unread_count'] = $this->buyer_unread_count + 1;
        }

        $this->update($data);
    }
}
