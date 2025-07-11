<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShortenUrl;
use App\Services\UrlShortener;
use App\Exceptions\InvalidUrlException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function __construct(
        private UrlShortener $urlShortenerService
    ) {}

    public function shorten(ShortenUrl $request): JsonResponse
    {
        try {
            $url = $this->urlShortenerService->shortenUrl($request->validated());

            return response()->json([
                'success' => true,
                'data' => [
                    'short_url' => $url->getShortUrl(),
                    'original_url' => $url->original_url,
                    'short_code' => $url->custom_code ?: $url->short_code,
                    'expires_at' => $url->expires_at,
                    'created_at' => $url->created_at,
                ]
            ], 201);
        } catch (InvalidUrlException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while shortening the URL.',
            ], 500);
        }
    }

    public function bulkShorten(Request $request): JsonResponse
    {
        $request->validate([
            'urls' => 'required|array|min:1|max:100',
            'urls.*' => 'required|url|max:2048',
        ]);

        $results = [];
        $errors = [];

        foreach ($request->urls as $index => $url) {
            try {
                $shortenedUrl = $this->urlShortenerService->shortenUrl(['url' => $url]);
                $results[] = [
                    'original_url' => $url,
                    'short_url' => $shortenedUrl->getShortUrl(),
                    'short_code' => $shortenedUrl->custom_code ?: $shortenedUrl->short_code,
                ];
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'url' => $url,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $results,
            'errors' => $errors,
        ]);
    }
}