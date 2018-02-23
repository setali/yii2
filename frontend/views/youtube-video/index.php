<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\YoutubeVideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Youtube Videos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="youtube-video-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php Pjax::begin(); ?>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
		<?= Html::a(Yii::t('app', 'Sync with Youtube'), ['youtube-video/sync'], ['class' => 'btn btn-success']) ?>
    </p>

	<?= GridView::widget([
		                     'dataProvider' => $dataProvider,
		                     'filterModel' => $searchModel,
		                     'columns' => [
			                     ['class' => 'yii\grid\SerialColumn'],

			                     'id',
			                     [
				                     'label' => Yii::t('app', 'Owner'),
				                     'attribute' => 'owner_id',
				                     'value' => 'owner.username',
			                     ],
			                     //            'channel_id',
			                     'channel_title',
			                     //            'video_id',
			                     'title',
			                     'description:ntext',
			                     //'published_at',

			                     ['class' => 'yii\grid\ActionColumn'],
		                     ],
	                     ]); ?>
	<?php Pjax::end(); ?>
</div>
