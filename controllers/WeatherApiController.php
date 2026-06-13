<?php

namespace app\controllers;

use app\models\WeatherHistory;
use app\services\WeatherService;
use Yii;
use yii\filters\Cors;
use yii\web\Controller;
use yii\web\Response;

class WeatherApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => Cors::class,
            ],
        ]);
    }

    public function actionIndex(?string $city = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $city = $city ?? (string)Yii::$app->request->get('city', '');
            $service = new WeatherService();
            $data = $service->getWeather($city);

            $history = new WeatherHistory([
                'query' => $data['query'],
                'city' => $data['location']['name'] ?? null,
                'country' => $data['location']['country'] ?? null,
                'latitude' => $data['location']['latitude'] ?? null,
                'longitude' => $data['location']['longitude'] ?? null,
                'temperature' => $data['current']['temperature'] ?? null,
                'apparent_temperature' => $data['current']['apparent_temperature'] ?? null,
                'humidity' => $data['current']['humidity'] ?? null,
                'wind_speed' => $data['current']['wind_speed'] ?? null,
                'weather_code' => $data['current']['weather_code'] ?? null,
                'weather_description' => $data['current']['weather_description'] ?? null,
                'raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'created_at' => time(),
            ]);
            $history->save(false);

            return [
                'success' => true,
                'history_id' => $history->id,
                'data' => $data,
            ];
        } catch (\Throwable $exception) {
            Yii::$app->response->statusCode = 422;

            return [
                'success' => false,
                'message' => $exception->getMessage(),
            ];
        }
    }
}
