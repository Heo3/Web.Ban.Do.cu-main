<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'price',
        'description',
        'image',
        'address',
        'province',
        'phone',
        'status',
        'views_count',
    ];

    /**
     * Auto generate slug before save if not provided
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title) . '-' . Str::random(6);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->price == 0) {
            return 'Cho tặng miễn phí';
        }
        return number_format($this->price, 0, ',', '.') . ' đ';
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '';
    }

    public function getDisplayImageAttribute(): string
    {
        if ($this->image) {
            if (Str::startsWith($this->image, ['http://', 'https://'])) {
                return $this->image;
            }
            $path = ltrim($this->image, '/');
            if (Str::startsWith($path, 'storage/')) {
                $path = substr($path, 8);
            }
            return asset('storage/' . $path);
        }

        // Check if there are related images
        $firstImage = $this->images->first();
        if ($firstImage) {
            if (Str::startsWith($firstImage->image_path, ['http://', 'https://'])) {
                return $firstImage->image_path;
            }
            $path = ltrim($firstImage->image_path, '/');
            if (Str::startsWith($path, 'storage/')) {
                $path = substr($path, 8);
            }
            return asset('storage/' . $path);
        }

        return 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=500&auto=format&fit=crop&q=60';
    }

    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->favorites->contains('user_id', $user->id);
    }
}
