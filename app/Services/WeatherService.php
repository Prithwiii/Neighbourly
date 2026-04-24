<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getForecastByCoordinates(float $latitude, float $longitude): ?array
    {
        $cacheKey = sprintf('weather_%s_%s', round($latitude, 2), round($longitude, 2));

        return Cache::remember($cacheKey, 1800, function () use ($latitude, $longitude) {
            $response = Http::withoutVerifying()
                ->timeout(12)
                ->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m',
                    'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max',
                    'timezone' => 'auto',
                    'forecast_days' => 7,
                ]);

            if (!$response->successful()) {
                return null;
            }

            return $response->json();
        });
    }

    public function weatherCodeToText(?int $code): string
    {
        return match ($code) {
            0 => 'Clear sky',
            1, 2, 3 => 'Partly cloudy',
            45, 48 => 'Fog',
            51, 53, 55 => 'Drizzle',
            56, 57 => 'Freezing drizzle',
            61, 63, 65 => 'Rain',
            66, 67 => 'Freezing rain',
            71, 73, 75, 77 => 'Snow',
            80, 81, 82 => 'Rain showers',
            85, 86 => 'Snow showers',
            95 => 'Thunderstorm',
            96, 99 => 'Thunderstorm with hail',
            default => 'Unknown weather',
        };
    }

    public function getPlaceNameByCoordinates(float $latitude, float $longitude): ?string
    {
        $cacheKey = sprintf('place_%s_%s', round($latitude, 3), round($longitude, 3));

        return Cache::remember($cacheKey, 86400, function () use ($latitude, $longitude) {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'NeighbourlyWeather/1.0',
                    'Accept-Language' => 'en',
                ])
                ->timeout(12)
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'jsonv2',
                    'lat' => $latitude,
                    'lon' => $longitude,
                ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            $address = $data['address'] ?? [];

            $locality = $address['city']
                ?? $address['town']
                ?? $address['village']
                ?? $address['suburb']
                ?? $address['county']
                ?? null;

            $country = $address['country'] ?? null;

            if ($locality && $country) {
                return $locality . ', ' . $country;
            }

            if (!empty($data['display_name'])) {
                $display = explode(',', (string) $data['display_name']);

                return trim($display[0]);
            }

            return null;
        });
    }
}
