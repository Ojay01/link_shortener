<?php

namespace App\Http\Controllers;

use App\Services\UrlShortener;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class RedirectController extends Controller
{
    
    public function __construct(
        private UrlShortener $urlShortenerService
    ) {}

    public function redirect(string $code): RedirectResponse|Response
    {
        $url = $this->urlShortenerService->findUrlByCode($code);

        if (!$url) {
            return response()->view('errors.404', [], 404);
        }

        // Record the click
        $this->urlShortenerService->recordClick($url);

        return redirect($url->original_url, 302);
    }
}