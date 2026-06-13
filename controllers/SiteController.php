<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\ErrorAction;

class SiteController extends Controller
{
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }

    public function actionAbout(): string
    {
        return $this->render('about');
    }

    public function actionContact(): string
    {
        return $this->render('contact');
    }

    public function actionApi(): string
    {
        return $this->render('api');
    }
}

