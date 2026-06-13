<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */

$this->title = $name;
?>
<section class="simple-hero error-page">
    <p class="eyebrow">Ошибка</p>
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= nl2br(Html::encode($message)) ?></p>
    <a class="button-link" href="/">Вернуться на главную</a>
</section>
