<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UrlShortener;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function __construct(
        private UrlShortener $urlShortenerService
    ) {}

    public function show(string $code): JsonResponse
    {
        $stats = $this->urlShortenerService->getStats($code);

        if (!$stats) {
            return response()->json([
                'success' => false,
                'message' => 'URL not found or has expired.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}