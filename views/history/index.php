<?php

use app\models\WeatherHistory;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var WeatherHistory[] $historyItems */

$this->title = 'История запросов';
?>
<section class="simple-hero">
    <p class="eyebrow">PostgreSQL</p>
    <h1>История запросов</h1>
    <p>
        Здесь отображаются города, по которым пользователи искали погоду.
        Таблица берёт данные из PostgreSQL после каждого успешного API-запроса.
    </p>
</section>

<section class="table-panel">
    <?php if (empty($historyItems)): ?>
        <div class="muted-card">Пока нет запросов. Вернитесь на главную страницу и найдите погоду для любого города.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="history-table">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Запрос</th>
                    <th>Город</th>
                    <th>Страна</th>
                    <th>Температура</th>
                    <th>Ощущается</th>
                    <th>Влажность</th>
                    <th>Ветер</th>
                    <th>Описание</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($historyItems as $item): ?>
                    <tr>
                        <td><?= Html::encode(date('d.m.Y H:i', $item->created_at)) ?></td>
                        <td><?= Html::encode($item->query) ?></td>
                        <td><?= Html::encode($item->city) ?></td>
                        <td><?= Html::encode($item->country) ?></td>
                        <td><?= Html::encode($item->temperature) ?> °C</td>
                        <td><?= Html::encode($item->apparent_temperature) ?> °C</td>
                        <td><?= Html::encode($item->humidity) ?>%</td>
                        <td><?= Html::encode($item->wind_speed) ?> км/ч</td>
                        <td><?= Html::encode($item->weather_description) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
