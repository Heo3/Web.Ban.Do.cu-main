<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'diachi',
        'dia_chi',
        'avatar',
    ];

    protected $appends = [
        'avatar_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy đường dẫn avatar đầy đủ và chính xác
     * Hỗ trợ cả link Google/mạng xã hội (http/https) và ảnh upload cục bộ
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return null;
        }

        if (Str::startsWith($this->avatar, ['http://', 'https://'])) {
            return $this->avatar;
        }

        $path = ltrim($this->avatar, '/');
        if (Str::startsWith($path, 'storage/')) {
            $path = substr($path, 8);
        }

        return asset('storage/' . $path);
    }
}