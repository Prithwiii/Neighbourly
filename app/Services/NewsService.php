<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsService
{
    public function getNews(string $sortBy = 'publishedAt', ?string $from = null, ?string $to = null, ?string $query = null): array
    {
        $topic = config('services.newsapi.topic');
        $key   = config('services.newsapi.key');

        $searchQuery = $query ?? $topic;
        $cacheKey = "news_{$searchQuery}_{$sortBy}_{$from}_{$to}";

        return Cache::remember($cacheKey, 3600, function () use ($searchQuery, $key, $sortBy, $from, $to) {
            $params = [
                'q'        => $searchQuery,
                'sortBy'   => $sortBy,
                'pageSize' => 10,
                'apiKey'   => $key,
            ];

            if ($from) $params['from'] = $from;
            if ($to)   $params['to']   = $to;

            $response = Http::withoutVerifying()->get('https://newsapi.org/v2/everything', $params);

            if ($response->successful()) {
                return $response->json('articles') ?? [];
            }

            return [];
        });
    }
}

