<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
$this->title = 'Погода в любом городе мира';
?>
<section class="hero">
    <div class="hero-content">
        <p class="eyebrow">Yii2 API + PostgreSQL + Docker</p>
        <h1>Погода в любом городе мира</h1>
        <p class="hero-text">
            Введите город на русском или английском языке. Приложение найдёт координаты,
            получит текущую погоду через API и сохранит запрос в историю PostgreSQL.
        </p>

        <form id="weatherForm" class="weather-form">
            <input id="cityInput" type="text" placeholder="Например: Москва, Paris, Tokyo" autocomplete="off" required>
            <button type="submit">Узнать погоду</button>
        </form>

        <div id="weatherResult" class="weather-result muted-card">
            Здесь появится результат поиска погоды.
        </div>
    </div>

    <div class="hero-panel">
        <div class="temperature-card">
            <span class="sun-icon">☀️</span>
            <strong>API работает со странами по всему миру</strong>
            <p>Город → координаты → текущая погода → сохранение в БД.</p>
        </div>
    </div>
</section>

<section class="section-block">
    <div class="section-title">
        <p class="eyebrow">Home: 3 картинки</p>
        <h2>Три погодных состояния</h2>
    </div>
    <div class="image-grid grid-3">
        <article class="image-card">
            <img src="/images/home-sunny.svg" alt="Солнечная погода">
            <h3>Солнечно</h3>
            <p>Ясный день, высокая видимость и комфортный прогноз.</p>
        </article>
        <article class="image-card">
            <img src="/images/home-rain.svg" alt="Дождливая погода">
            <h3>Дождь</h3>
            <p>API показывает осадки, влажность и скорость ветра.</p>
        </article>
        <article class="image-card">
            <img src="/images/home-night.svg" alt="Ночная погода">
            <h3>Ночь</h3>
            <p>Погода доступна для разных часовых поясов и стран.</p>
        </article>
    </div>
</section>
