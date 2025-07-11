<?php

namespace Database\Seeders;

use App\Models\Url;
use App\Models\UrlClick;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UrlSeeder extends Seeder
{
    public function run(): void
    {
        $urls = [
            [
                'original_url' => 'https://laravel.com/docs',
                'short_code' => 'laravel',
                'custom_code' => 'docs',
                'click_count' => 42,
            ],
            [
                'original_url' => 'https://github.com/Ojay01',
                'short_code' => 'github',
                'click_count' => 25,
            ],
            [
                'original_url' => 'https://kellykings.design/',
                'short_code' => 'kelly-kings',
                'click_count' => 15,
            ],
        ];

        foreach ($urls as $urlData) {
            $url = Url::create($urlData);

            // Create some sample clicks
            for ($i = 0; $i < $url->click_count; $i++) {
                UrlClick::create([
                    'url_id' => $url->id,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Test Browser)',
                    'clicked_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}