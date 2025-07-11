<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Details - {{ $url->getShortUrl() }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto p-6">
        <!-- Navigation -->
        <div class="mb-6">
            <a href="{{ route('url-stats.index') }}" 
               class="text-blue-600 hover:text-blue-800 flex items-center">
                ← Back to Dashboard
            </a>
        </div>
        
        <!-- URL Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">URL Details</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Original URL</h3>
                    <p class="text-gray-900 break-all">{{ $url->original_url }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Short URL</h3>
                    <div class="flex items-center space-x-2">
                        <p class="text-blue-600 font-mono">{{ $url->getShortUrl() }}</p>
                        <button onclick="copyToClipboard('{{ $url->getShortUrl() }}')" 
                                class="text-sm bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded">
                            Copy
                        </button>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Total Clicks</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $url->click_count }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                    <div class="flex items-center space-x-2">
                        @if($url->isActive())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                        
                        @if($url->isExpired())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Expired
                            </span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Created</h3>
                    <p class="text-gray-900">{{ $url->created_at->format('M d, Y g:i A') }}</p>
                </div>
                
                @if($url->expires_at)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Expires</h3>
                        <p class="text-gray-900">{{ $url->expires_at->format('M d, Y g:i A') }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        
        <!-- Recent Clicks -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold">Recent Clicks (Last 50)</h2>
            </div>
            
            @if($url->clicks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referer</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($url->clicks as $click)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $click->clicked_at->format('M d, Y g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                        {{ $click->ip_address }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $click->user_agent }}">
                                        {{ $click->user_agent ?: 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $click->referer }}">
                                        {{ $click->referer ?: 'Direct' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center text-gray-500">
                    <p>No clicks recorded yet.</p>
                </div>
            @endif
        </div>
    </div>
    
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // You could show a toast notification here
                console.log('Copied to clipboard');
            });
        }
        

    </script>
</body>
</html>