<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UrlClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_id',
        'ip_address',
        'user_agent',
        'referer',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function url(): BelongsTo
    {
        return $this->belongsTo(Url::class);
    }
}
