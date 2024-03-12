<?php

namespace app\models\search;

use app\models\History;
use app\models\Sms;
use Yii;
use yii\base\Model;
use yii\bootstrap\Html;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;

/**
 * HistorySearch represents the model behind the search form about `app\models\History`.
 *
 * @property array $objects
 */
class HistorySearch extends History
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public static function search(array $params): ActiveDataProvider
    {
        // Define a cache key based on the parameters
        $cacheKey = ['history_search', md5(serialize($params))];
        // Check if data exists in cache
        $dataProvider = Yii::$app->cache->get($cacheKey);
        if ($dataProvider === false) {
            $query = History::find()->with([
                'customer',
                'user',
                'sms',
                'task',
                'call',
                'fax',
            ])->limit(20)->offset(20 * ($params['page'] - 1));

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $dataProvider->setSort([
                'defaultOrder' => [
                    'ins_ts' => SORT_DESC,
                    'id' => SORT_DESC
                ],
            ]);

            $model = new static();
            $model->load($params);

            if (!$model->validate()) {
                // No need to return any records when validation fails
                $query->where('0=1');
            }

            // Store data in cache with a dependency on the database schema
            Yii::$app->cache->set(
                $cacheKey,
                $dataProvider,
                null,
                new DbDependency([
                    'sql' => 'SELECT MAX(ins_ts) FROM ' . History::tableName(),
                ])
            );
        }

        return $dataProvider;
    }


}
