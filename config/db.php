<?php

return [
    'class' => yii\db\Connection::class,
    'dsn' => 'pgsql:host=' . (getenv('DB_HOST') ?: 'db')
        . ';port=' . (getenv('DB_PORT') ?: '5432')
        . ';dbname=' . (getenv('DB_NAME') ?: 'weather_db'),
    'username' => getenv('DB_USER') ?: 'weather_user',
    'password' => getenv('DB_PASSWORD') ?: 'weather_password',
    'charset' => 'utf8',
    'enableSchemaCache' => false,
];
