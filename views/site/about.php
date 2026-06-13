<?php

/** @var yii\web\View $this */
$this->title = 'О проекте';
?>
<section class="simple-hero">
    <p class="eyebrow">About: 5 картинок</p>
    <h1>О проекте</h1>
    <p>
        Этот проект показывает, как на Yii2 сделать API-приложение в Docker:
        пользователь вводит город, Yii2 обращается к погодному сервису, получает JSON-ответ
        и сохраняет историю запросов в PostgreSQL.
    </p>
</section>

<section class="image-grid grid-5">
    <article class="image-card">
        <img src="/images/about-api.svg" alt="API">
        <h3>API-запрос</h3>
        <p>Клиент отправляет город на endpoint <code>/api/weather</code>.</p>
    </article>
    <article class="image-card">
        <img src="/images/about-map.svg" alt="Карта">
        <h3>Геокодинг</h3>
        <p>Название города преобразуется в широту и долготу.</p>
    </article>
    <article class="image-card">
        <img src="/images/about-cloud.svg" alt="Облако">
        <h3>Погода</h3>
        <p>По координатам загружается текущая температура и ветер.</p>
    </article>
    <article class="image-card">
        <img src="/images/about-db.svg" alt="База данных">
        <h3>PostgreSQL</h3>
        <p>Каждый успешный запрос сохраняется в историю.</p>
    </article>
    <article class="image-card">
        <img src="/images/about-docker.svg" alt="Docker">
        <h3>Docker</h3>
        <p>Проект запускается одинаково на ПК, ноутбуке или сервере.</p>
    </article>
</section>

<section class="info-panel">
    <h2>Что сохраняется в истории?</h2>
    <p>
        В таблицу попадает исходный запрос пользователя, найденный город, страна,
        координаты, температура, ощущаемая температура, влажность, ветер, описание погоды
        и полный JSON-ответ API.
    </p>
</section>
