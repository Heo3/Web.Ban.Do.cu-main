<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailVietnamese;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'sdt',
        'email',
        'password',
        'role',
        'status',
        'google_id',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ==================== STATUS / ROLE CHECKS ====================

    public function isAdmin(): bool
    {
        return ($this->role ?? '') === 'admin' || $this->email === 'admin@gmail.com';
    }

    public function isBanned(): bool
    {
        return ($this->status ?? 'active') === 'banned';
    }

    public function isVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Gửi notification xác minh email bằng tiếng Việt.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailVietnamese);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->profile?->avatar_url;
    }

    // ==================== RELATIONSHIPS ====================

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

    public function buyerConversations()
    {
        return $this->hasMany(Conversation::class, 'buyer_id');
    }

    public function sellerConversations()
    {
        return $this->hasMany(Conversation::class, 'seller_id');
    }

    public function conversations()
    {
        return Conversation::where(function ($query) {
            $query->where('buyer_id', $this->id)
                  ->orWhere('seller_id', $this->id);
        });
    }

    public function totalUnreadMessagesCount(): int
    {
        $buyerUnread = Conversation::where('buyer_id', $this->id)->sum('buyer_unread_count');
        $sellerUnread = Conversation::where('seller_id', $this->id)->sum('seller_unread_count');
        return (int) ($buyerUnread + $sellerUnread);
    }
}

