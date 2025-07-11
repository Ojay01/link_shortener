<?php

namespace App\Http\Controllers;
use App\Models\Url;
use App\Models\UrlClick;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteController extends Controller
{
       public function index()
    {
        $urls = Url::with('clicks')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('welcome', compact('urls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
            'custom_code' => 'nullable|string|max:50|unique:urls,custom_code',
            'expires_at' => 'nullable|date|after:now',
        ]);
        
        $url = Url::create([
            'original_url' => $request->original_url,
            'short_code' => Str::random(8),
            'custom_code' => $request->custom_code,
            'expires_at' => $request->expires_at,
            'click_count' => 0,
            'is_active' => true,
            'created_by_ip' => $request->ip(),
        ]);
        
        return redirect()->back()->with('success', 'URL created successfully!');
    }
    
    public function show(Url $url)
    {
        $url->load(['clicks' => function ($query) {
            $query->orderBy('clicked_at', 'desc')->limit(50);
        }]);
       
        
        return view('details', compact('url'));
    }
    
    public function toggle(Url $url)
    {
        $url->update(['is_active' => !$url->is_active]);
        
        return redirect()->back()->with('success', 'URL status updated!');
    }
}
