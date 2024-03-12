<?php

namespace app\widgets\HistoryList;

use app\models\search\HistorySearch;
use kartik\export\ExportMenu;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;

class HistoryList extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        $model = new HistorySearch();

        return $this->render('main', [
            'model' => $model,
            'linkExport' => $this->getLinkExport('Csv'),
            'pdfExport' => $this->getLinkExport('Pdf'),
            'xlsExport' => $this->getLinkExport('Xls'),
            'dataProvider' => $model->search(Yii::$app->request->queryParams)
        ]);
    }

    /**
     * @param  string $type
     * @return string
     */
    private function getLinkExport(string $type): string
    {
        $exportTypes = [ExportMenu::FORMAT_CSV, ExportMenu::FORMAT_PDF, ExportMenu::FORMAT_EXCEL];
        $params = Yii::$app->getRequest()->getQueryParams();
        if (!in_array('page', $params)) {
            $params = $params + ['page' => 1];
        }
        $params = ArrayHelper::merge([
            'exportType' => in_array($type,$exportTypes) ? $type : 'Csv'
        ], $params);
        $params[0] = 'site/export';

        return Url::to($params);
    }
}
