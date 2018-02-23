<?php
/**
 * Created by PhpStorm.
 * User: madris
 * Date: 2/23/18
 * Time: 6:11 AM
 */

namespace frontend\controllers;


use yii\rest\ActiveController;

class VideoController extends ActiveController
{
	public $modelClass = 'backend\models\YoutubeVideo';

	public function behaviors()
	{
		return [
			[
				'class' => \yii\filters\ContentNegotiator::className(),
				'formats' => [
					'application/json' => \yii\web\Response::FORMAT_JSON,
				],
			],
		];
	}
}