<?php

namespace app\services;

use RuntimeException;
use Yii;
use yii\helpers\Json;

class WeatherService
{
    private const GEOCODING_URL = 'https://geocoding-api.open-meteo.com/v1/search';
    private const FORECAST_URL = 'https://api.open-meteo.com/v1/forecast';
    private const WTTR_URL = 'https://wttr.in';
    private const CACHE_TTL_SECONDS = 600;

    public function getWeather(string $city): array
    {
        $city = trim($city);

        if ($city === '') {
            throw new RuntimeException('Введите название города.');
        }

        $cached = $this->getFromCache($city);
        if ($cached !== null) {
            $cached['cached'] = true;
            return $cached;
        }

        try {
            $data = $this->getWeatherFromOpenMeteo($city);
        } catch (\Throwable $openMeteoException) {
            try {
                $data = $this->getWeatherFromWttr($city);
                $data['fallback_reason'] = $openMeteoException->getMessage();
            } catch (\Throwable $wttrException) {
                throw new RuntimeException(
                    'Не удалось получить погоду. Open-Meteo: ' . $openMeteoException->getMessage()
                    . ' Дополнительный API: ' . $wttrException->getMessage()
                );
            }
        }

        $this->saveToCache($city, $data);
        return $data;
    }

    private function getWeatherFromOpenMeteo(string $city): array
    {
        $place = $this->findCity($city);
        $weather = $this->loadWeather((float)$place['latitude'], (float)$place['longitude']);
        $current = $weather['current'] ?? [];
        $units = $weather['current_units'] ?? [];
        $weatherCode = isset($current['weather_code']) ? (int)$current['weather_code'] : null;

        return [
            'query' => $city,
            'location' => [
                'name' => $place['name'] ?? null,
                'country' => $place['country'] ?? null,
                'country_code' => $place['country_code'] ?? null,
                'admin1' => $place['admin1'] ?? null,
                'latitude' => $place['latitude'] ?? null,
                'longitude' => $place['longitude'] ?? null,
                'timezone' => $place['timezone'] ?? null,
            ],
            'current' => [
                'time' => $current['time'] ?? null,
                'temperature' => $current['temperature_2m'] ?? null,
                'temperature_unit' => $units['temperature_2m'] ?? '°C',
                'apparent_temperature' => $current['apparent_temperature'] ?? null,
                'apparent_temperature_unit' => $units['apparent_temperature'] ?? '°C',
                'humidity' => $current['relative_humidity_2m'] ?? null,
                'humidity_unit' => $units['relative_humidity_2m'] ?? '%',
                'wind_speed' => $current['wind_speed_10m'] ?? null,
                'wind_speed_unit' => $units['wind_speed_10m'] ?? 'km/h',
                'weather_code' => $weatherCode,
                'weather_description' => $this->weatherCodeToText($weatherCode),
            ],
            'source' => 'Open-Meteo',
            'cached' => false,
        ];
    }

    private function getWeatherFromWttr(string $city): array
    {
        $url = self::WTTR_URL . '/' . rawurlencode($city) . '?' . http_build_query([
            'format' => 'j1',
            'lang' => 'ru',
        ]);

        $data = $this->requestJson($url);
        $current = $data['current_condition'][0] ?? null;
        $nearest = $data['nearest_area'][0] ?? [];

        if (!is_array($current)) {
            throw new RuntimeException('дополнительный погодный API вернул пустую текущую погоду.');
        }

        $weatherDescription = $current['lang_ru'][0]['value']
            ?? $current['weatherDesc'][0]['value']
            ?? 'Нет описания';

        $weatherCode = isset($current['weatherCode']) ? (int)$current['weatherCode'] : null;

        return [
            'query' => $city,
            'location' => [
                'name' => $nearest['areaName'][0]['value'] ?? $city,
                'country' => $nearest['country'][0]['value'] ?? null,
                'country_code' => null,
                'admin1' => $nearest['region'][0]['value'] ?? null,
                'latitude' => $nearest['latitude'] ?? null,
                'longitude' => $nearest['longitude'] ?? null,
                'timezone' => null,
            ],
            'current' => [
                'time' => $current['localObsDateTime'] ?? $current['observation_time'] ?? null,
                'temperature' => isset($current['temp_C']) ? (float)$current['temp_C'] : null,
                'temperature_unit' => '°C',
                'apparent_temperature' => isset($current['FeelsLikeC']) ? (float)$current['FeelsLikeC'] : null,
                'apparent_temperature_unit' => '°C',
                'humidity' => isset($current['humidity']) ? (int)$current['humidity'] : null,
                'humidity_unit' => '%',
                'wind_speed' => isset($current['windspeedKmph']) ? (float)$current['windspeedKmph'] : null,
                'wind_speed_unit' => 'km/h',
                'weather_code' => $weatherCode,
                'weather_description' => $weatherDescription,
            ],
            'source' => 'wttr.in fallback',
            'cached' => false,
        ];
    }

    private function findCity(string $city): array
    {
        $url = self::GEOCODING_URL . '?' . http_build_query([
            'name' => $city,
            'count' => 1,
            'language' => 'ru',
            'format' => 'json',
        ]);

        $data = $this->requestJson($url);
        $results = $data['results'] ?? [];

        if (empty($results[0])) {
            throw new RuntimeException('Город не найден. Попробуйте ввести название на русском или английском языке.');
        }

        return $results[0];
    }

    private function loadWeather(float $latitude, float $longitude): array
    {
        $url = self::FORECAST_URL . '?' . http_build_query([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,apparent_temperature,relative_humidity_2m,weather_code,wind_speed_10m',
            'timezone' => 'auto',
            'forecast_days' => 1,
        ]);

        return $this->requestJson($url);
    }

    private function requestJson(string $url): array
    {
        $response = null;
        $httpCode = 0;
        $error = null;

        if (function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_USERAGENT => 'Yii2WeatherApi/1.0 (+https://render.com)',
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_ENCODING => '',
            ]);

            $response = curl_exec($curl);
            $httpCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 20,
                    'header' => "User-Agent: Yii2WeatherApi/1.0 (+https://render.com)\r\nAccept: application/json\r\n",
                ],
            ]);

            $response = @file_get_contents($url, false, $context);
            $headers = $http_response_header ?? [];
            foreach ($headers as $header) {
                if (preg_match('/^HTTP\/\S+\s+(\d+)/', $header, $matches)) {
                    $httpCode = (int)$matches[1];
                    break;
                }
            }
            $lastError = error_get_last();
            $error = $lastError['message'] ?? null;
        }

        if ($response === false || $response === null || $response === '') {
            $message = 'не удалось получить ответ от внешнего API.';
            if ($error) {
                $message .= ' Техническая причина: ' . $error;
            }
            throw new RuntimeException($message);
        }

        if ($httpCode >= 400) {
            if ($httpCode === 429) {
                throw new RuntimeException('внешний API временно ограничил количество запросов, HTTP 429.');
            }
            throw new RuntimeException('внешний API вернул HTTP ' . $httpCode . '.');
        }

        try {
            $data = Json::decode($response, true);
        } catch (\Throwable $exception) {
            throw new RuntimeException('внешний API вернул некорректный JSON.');
        }

        if (!is_array($data)) {
            throw new RuntimeException('внешний API вернул пустой ответ.');
        }

        return $data;
    }

    private function getFromCache(string $city): ?array
    {
        $file = $this->getCacheFile($city);

        if (!is_file($file)) {
            return null;
        }

        if ((time() - filemtime($file)) > self::CACHE_TTL_SECONDS) {
            @unlink($file);
            return null;
        }

        $contents = @file_get_contents($file);
        if ($contents === false || $contents === '') {
            return null;
        }

        try {
            $data = Json::decode($contents, true);
        } catch (\Throwable $exception) {
            return null;
        }

        return is_array($data) ? $data : null;
    }

    private function saveToCache(string $city, array $data): void
    {
        $file = $this->getCacheFile($city);
        $dir = dirname($file);

        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        @file_put_contents($file, Json::encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function getCacheFile(string $city): string
    {
        $normalized = mb_strtolower(trim($city), 'UTF-8');
        return Yii::getAlias('@runtime/weather-cache/') . sha1($normalized) . '.json';
    }

    private function weatherCodeToText(?int $code): string
    {
        return match ($code) {
            0 => 'Ясно',
            1 => 'Преимущественно ясно',
            2 => 'Переменная облачность',
            3 => 'Пасмурно',
            45, 48 => 'Туман',
            51, 53, 55 => 'Морось',
            56, 57 => 'Ледяная морось',
            61, 63, 65 => 'Дождь',
            66, 67 => 'Ледяной дождь',
            71, 73, 75 => 'Снег',
            77 => 'Снежные зёрна',
            80, 81, 82 => 'Ливень',
            85, 86 => 'Снежный ливень',
            95 => 'Гроза',
            96, 99 => 'Гроза с градом',
            default => 'Нет описания',
        };
    }
}
