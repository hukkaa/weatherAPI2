<?php

use yii\db\Migration;

class m260613_000001_create_weather_history_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%weather_history}}', [
            'id' => $this->primaryKey(),
            'query' => $this->string(255)->notNull()->comment('Что ввёл пользователь'),
            'city' => $this->string(255)->comment('Найденный город'),
            'country' => $this->string(255)->comment('Страна'),
            'latitude' => $this->decimal(10, 6)->comment('Широта'),
            'longitude' => $this->decimal(10, 6)->comment('Долгота'),
            'temperature' => $this->decimal(6, 2)->comment('Температура'),
            'apparent_temperature' => $this->decimal(6, 2)->comment('Ощущается как'),
            'humidity' => $this->integer()->comment('Влажность'),
            'wind_speed' => $this->decimal(6, 2)->comment('Скорость ветра'),
            'weather_code' => $this->integer()->comment('Код погоды'),
            'weather_description' => $this->string(255)->comment('Описание погоды'),
            'raw_response' => 'JSONB',
            'created_at' => $this->integer()->notNull()->comment('Дата запроса'),
        ]);

        $this->createIndex('idx-weather_history-created_at', '{{%weather_history}}', 'created_at');
        $this->createIndex('idx-weather_history-query', '{{%weather_history}}', 'query');
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%weather_history}}');
    }
}
