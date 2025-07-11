<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;


class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_url',
        'short_code',
        'custom_code',
        'expires_at',
        'click_count',
        'is_active',
        'created_by_ip',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'click_count' => 'integer',
    ];

    public function clicks(): HasMany
    {
        return $this->hasMany(UrlClick::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }

    public function getShortUrl(): string
    {
        $code = $this->custom_code ?: $this->short_code;
        return url($code);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }
}
