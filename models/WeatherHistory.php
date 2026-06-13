<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $query
 * @property string|null $city
 * @property string|null $country
 * @property string|null $weather_description
 * @property int $created_at
 */
class WeatherHistory extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%weather_history}}';
    }

    public function rules(): array
    {
        return [
            [['query', 'created_at'], 'required'],
            [['latitude', 'longitude', 'temperature', 'apparent_temperature', 'wind_speed'], 'number'],
            [['humidity', 'weather_code', 'created_at'], 'integer'],
            [['raw_response'], 'string'],
            [['query', 'city', 'country', 'weather_description'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'query' => 'Запрос пользователя',
            'city' => 'Город',
            'country' => 'Страна',
            'latitude' => 'Широта',
            'longitude' => 'Долгота',
            'temperature' => 'Температура, °C',
            'apparent_temperature' => 'Ощущается как, °C',
            'humidity' => 'Влажность, %',
            'wind_speed' => 'Ветер, км/ч',
            'weather_code' => 'Код погоды',
            'weather_description' => 'Описание',
            'raw_response' => 'Ответ API',
            'created_at' => 'Дата запроса',
        ];
    }
}
