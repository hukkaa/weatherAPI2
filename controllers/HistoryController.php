<?php

namespace app\controllers;

use app\models\WeatherHistory;
use yii\web\Controller;

class HistoryController extends Controller
{
    public function actionIndex(): string
    {
        $historyItems = WeatherHistory::find()
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
            ->limit(100)
            ->all();

        return $this->render('index', [
            'historyItems' => $historyItems,
        ]);
    }
}
