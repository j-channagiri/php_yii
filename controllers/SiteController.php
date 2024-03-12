<?php

namespace app\controllers;

use app\models\search\HistorySearch;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }


    /**
     * @param string $exportType
     * @return string
     */
    public function actionExport(string $exportType): string
    {
        $dataProvider = HistorySearch::search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'defaultOrder' => [
                'ins_ts' => SORT_DESC,
                'id' => SORT_DESC
            ],
        ]);

        return $this->render('export', [
            'dataProvider' => $dataProvider,
            'exportType' => $exportType,
            'model' => new HistorySearch(),
        ]);
    }
}
