<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function __construct(protected WeatherService $weatherService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $latitude = $request->input('lat', $user?->lat);
        $longitude = $request->input('lng', $user?->lng);

        if (!is_numeric($latitude) || !is_numeric($longitude)) {
            return view('weather.index', [
                'forecast' => null,
                'forecastRows' => [],
                'placeName' => null,
                'error' => 'Location is not set yet. Allow location access from Home so we can show weather for your area.',
            ]);
        }

        $latitude = (float) $latitude;
        $longitude = (float) $longitude;

        $forecast = $this->weatherService->getForecastByCoordinates($latitude, $longitude);
        $placeName = $this->weatherService->getPlaceNameByCoordinates($latitude, $longitude) ?? 'Your area';

        if (!$forecast || empty($forecast['daily']['time'])) {
            return view('weather.index', [
                'forecast' => null,
                'forecastRows' => [],
                'placeName' => $placeName,
                'error' => 'Unable to load weather forecast right now. Please try again in a moment.',
            ]);
        }

        $daily = $forecast['daily'];
        $rows = [];

        foreach ($daily['time'] as $index => $date) {
            $code = $daily['weather_code'][$index] ?? null;

            $rows[] = [
                'date' => $date,
                'summary' => $this->weatherService->weatherCodeToText($code),
                'max' => $daily['temperature_2m_max'][$index] ?? null,
                'min' => $daily['temperature_2m_min'][$index] ?? null,
                'rain_chance' => $daily['precipitation_probability_max'][$index] ?? null,
            ];
        }

        return view('weather.index', [
            'forecast' => $forecast,
            'forecastRows' => $rows,
            'placeName' => $placeName,
            'error' => null,
        ]);
    }
}
