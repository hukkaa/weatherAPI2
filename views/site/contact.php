<?php

/** @var yii\web\View $this */
$this->title = 'API и контакты проекта';
?>
<section class="simple-hero">
    <p class="eyebrow">Contact: 6 картинок</p>
    <h1>API проекта</h1>
    <p>
        Эта страница оформлена как справка по проекту. Ниже показаны основные возможности,
        которые можно проверить после запуска контейнеров.
    </p>
</section>

<section class="image-grid grid-6">
    <article class="image-card compact">
        <img src="/images/contact-search.svg" alt="Поиск">
        <h3>Поиск города</h3>
        <p>Поддерживаются русские и английские названия.</p>
    </article>
    <article class="image-card compact">
        <img src="/images/contact-json.svg" alt="JSON">
        <h3>JSON API</h3>
        <p><code>/api/weather?city=Tokyo</code></p>
    </article>
    <article class="image-card compact">
        <img src="/images/contact-history.svg" alt="История">
        <h3>История</h3>
        <p>Все успешные запросы отображаются в таблице.</p>
    </article>
    <article class="image-card compact">
        <img src="/images/contact-postgres.svg" alt="PostgreSQL">
        <h3>PostgreSQL</h3>
        <p>Данные хранятся в отдельном контейнере.</p>
    </article>
    <article class="image-card compact">
        <img src="/images/contact-docker.svg" alt="Docker">
        <h3>Docker Compose</h3>
        <p>Одна команда запускает сайт и базу.</p>
    </article>
    <article class="image-card compact">
        <img src="/images/contact-world.svg" alt="Мир">
        <h3>Весь мир</h3>
        <p>Можно искать города из разных стран.</p>
    </article>
</section>

<section class="info-panel api-panel">
    <h2>Пример API-ответа</h2>
    <pre><code>{
  "success": true,
  "data": {
    "query": "Tokyo",
    "location": { "name": "Токио", "country": "Япония" },
    "current": { "temperature": 22.4, "weather_description": "Переменная облачность" }
  }
}</code></pre>
</section>
