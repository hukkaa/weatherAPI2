<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $content */
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title ?: Yii::$app->name) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/site.css">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header class="main-header">
    <a href="/" class="logo">☁️ Weather Yii2</a>
    <nav class="main-nav">
        <a href="/">Главная</a>
        <a href="/about">О проекте</a>
        <a href="/api">API-тестер</a>
        <a href="/history">История запросов</a>
        <a href="/contact">Справка</a>
    </nav>
</header>

<main class="page-wrapper">
    <?= $content ?>
</main>

<footer class="main-footer">
    <div>
        <strong>Yii2 Weather API</strong> — учебный проект с Docker, PostgreSQL и историей запросов.
    </div>
    <div class="footer-muted">Данные погоды: Open-Meteo. Геокодинг и прогноз выполняются через внешний API.</div>
</footer>

<script src="/js/weather.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
