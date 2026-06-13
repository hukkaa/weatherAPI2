<?php

/** @var yii\web\View $this */
$this->title = 'API-тестер погоды';
?>
<section class="simple-hero api-tester-hero">
    <p class="eyebrow">Интерактивная страница API</p>
    <h1>API-тестер погоды</h1>
    <p>
        Здесь можно открыть именно страницу API, ввести город и получить JSON-ответ прямо в браузере.
        Каждый успешный запрос автоматически сохраняется в таблицу «История запросов» PostgreSQL.
    </p>
</section>

<section class="api-tester-layout">
    <div class="info-panel api-console">
        <h2>Отправить GET-запрос</h2>
        <p>
            Endpoint проекта: <code>/api/weather?city=Название_города</code>.
            Можно вводить города на русском или английском языке: Москва, Paris, Tokyo, Нью-Йорк.
        </p>

        <form id="apiTesterForm" class="weather-form api-form">
            <input id="apiCityInput" type="text" placeholder="Например: Москва, London, Tokyo" autocomplete="off" required>
            <button type="submit">Выполнить API-запрос</button>
        </form>

        <div class="api-url-box">
            <span>URL запроса:</span>
            <code id="apiRequestUrl">/api/weather?city=</code>
        </div>

        <div id="apiTesterStatus" class="weather-result muted-card">
            Введите город и нажмите кнопку, чтобы увидеть ответ API.
        </div>
    </div>

    <div class="info-panel api-docs-card">
        <h2>Как проверить вручную</h2>
        <p>Можно открыть API и без формы, прямо через адресную строку браузера:</p>
        <pre><code>http://localhost:8090/api/weather?city=Tokyo</code></pre>
        <p>Или через curl:</p>
        <pre><code>curl "http://localhost:8090/api/weather?city=Москва"</code></pre>
        <a class="button-link" href="/history">Открыть историю запросов →</a>
    </div>
</section>

<section class="info-panel api-panel">
    <h2>JSON-ответ API</h2>
    <pre id="apiJsonOutput"><code>{
  "success": true,
  "message": "Здесь появится полный JSON-ответ после запроса"
}</code></pre>
</section>
