<?php

namespace App\Services;

use App\Models\Url;
use App\Models\UrlClick;
use App\Exceptions\InvalidUrlException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UrlShortener
{
    private const SHORT_CODE_LENGTH = 6;
    private const MAX_ATTEMPTS = 10;

    public function shortenUrl(array $data): Url
    {
        $this->validateUrl($data['url']);

        // Check if URL already exists and is active
        $existingUrl = Url::where('original_url', $data['url'])
                          ->active()
                          ->first();

        if ($existingUrl) {
            return $existingUrl;
        }

        $shortCode = $this->generateUniqueShortCode();
        $customCode = $data['custom_code'] ?? null;

        if ($customCode) {
            $this->validateCustomCode($customCode);
        }

        return Url::create([
            'original_url' => $data['url'],
            'short_code' => $shortCode,
            'custom_code' => $customCode,
            'expires_at' => isset($data['expires_at']) ? Carbon::parse($data['expires_at']) : null,
            'created_by_ip' => request()->ip(),
        ]);
    }

    public function findUrlByCode(string $code): ?Url
    {
        return Url::where(function ($query) use ($code) {
            $query->where('short_code', $code)
                  ->orWhere('custom_code', $code);
        })
        ->active()
        ->first();
    }

    public function recordClick(Url $url): UrlClick
    {
        $url->incrementClickCount();

        return UrlClick::create([
            'url_id' => $url->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
            'clicked_at' => now(),
        ]);
    }

    public function getStats(string $code): ?array
    {
        $url = $this->findUrlByCode($code);

        if (!$url) {
            return null;
        }

        $recentClicks = $url->clicks()
                           ->where('clicked_at', '>=', now()->subDays(30))
                           ->orderBy('clicked_at', 'desc')
                           ->get();

        return [
            'original_url' => $url->original_url,
            'short_code' => $url->custom_code ?: $url->short_code,
            'short_url' => $url->getShortUrl(),
            'click_count' => $url->click_count,
            'created_at' => $url->created_at,
            'expires_at' => $url->expires_at,
            'is_active' => $url->isActive(),
            'recent_clicks' => $recentClicks->map(function ($click) {
                return [
                    'clicked_at' => $click->clicked_at,
                    'ip_address' => $click->ip_address,
                    'user_agent' => $click->user_agent,
                    'referer' => $click->referer,
                ];
            }),
        ];
    }

    private function generateUniqueShortCode(): string
    {
        $attempts = 0;

        do {
            $shortCode = $this->generateShortCode();
            $exists = Url::where('short_code', $shortCode)->exists();
            $attempts++;

            if ($attempts >= self::MAX_ATTEMPTS) {
                throw new \Exception('Unable to generate unique short code');
            }
        } while ($exists);

        return $shortCode;
    }

    private function generateShortCode(): string
    {
        return Str::random(self::SHORT_CODE_LENGTH);
    }

    private function validateUrl(string $url): void
    {
        $validator = Validator::make(['url' => $url], [
            'url' => 'required|url|max:2048'
        ]);

        if ($validator->fails()) {
            throw new InvalidUrlException('Invalid URL provided');
        }

        // Additional security checks
        $parsed = parse_url($url);
        if (!$parsed || !isset($parsed['scheme']) || !in_array($parsed['scheme'], ['http', 'https'])) {
            throw new InvalidUrlException('Only HTTP and HTTPS URLs are allowed');
        }
    }

    private function validateCustomCode(string $customCode): void
    {
        $validator = Validator::make(['custom_code' => $customCode], [
            'custom_code' => 'required|string|min:3|max:50|alpha_dash|unique:urls,custom_code'
        ]);

        if ($validator->fails()) {
            throw new InvalidUrlException('Invalid custom code: ' . $validator->errors()->first());
        }
    }
}