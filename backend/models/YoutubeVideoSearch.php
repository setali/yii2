<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\YoutubeVideo;

/**
 * YoutubeVideoSearch represents the model behind the search form of `backend\models\YoutubeVideo`.
 */
class YoutubeVideoSearch extends YoutubeVideo
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'published_at'], 'integer'],
			[['channel_id', 'channel_title', 'video_id', 'title', 'description', 'owner_id'], 'safe'],
		];
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
	 * @param array $filters
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $filters = [])
	{
		$query = YoutubeVideo::find();
		$query->joinWith(['owner']);

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			                                       'query' => $query,
		                                       ]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			                       'id' => $this->id,
			                       'owner_id' => $this->owner_id,
			                       'published_at' => $this->published_at,
		                       ]);

		$query->andFilterWhere(['like', 'channel_id', $this->channel_id])
		      ->andFilterWhere(['like', 'channel_title', $this->channel_title])
		      ->andFilterWhere(['like', 'video_id', $this->video_id])
		      ->andFilterWhere(['like', 'title', $this->title])
		      ->andFilterWhere(['like', 'description', $this->description])
		      ->andFilterWhere(['like', 'user.username', $this->owner]);

		foreach ($filters as $filter) {
			$query->andFilterWhere($filter);
		}

		return $dataProvider;
	}
}
