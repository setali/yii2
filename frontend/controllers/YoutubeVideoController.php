<?php

namespace frontend\controllers;

use Yii;
use backend\models\YoutubeVideo;
use backend\models\YoutubeVideoSearch;
use yii\filters\AccessControl;
use yii\web\View;

class YoutubeVideoController extends \yii\web\Controller {

	private $OAUTH2_CLIENT_ID     = '454245008749-8thj7pk1s5isqdgvvotjm72oo5ab2p74.apps.googleusercontent.com';

	private $OAUTH2_CLIENT_SECRET = 'yyKO1ZJsDkL2KUchvUJcvuTO';

	private $redirect             = 'http://yii.dev/frontend/web/youtube-video/success';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => ['my-video', 'sync', 'success'],
				'rules' => [
					[
						'actions' => ['my-video', 'sync', 'success'],
						'allow'   => TRUE,
						'roles'   => ['@'],
					],
				],
			],
		];
	}

	/**
	 * Displays User's videos.
	 *
	 * @return mixed
	 */
	public function actionMyVideo() {
		$searchModel = new YoutubeVideoSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, [
			['like', 'owner_id', Yii::$app->user->identity->getId()],
		]);

		return $this->render('my-video', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'message'      => '',
		]);
	}

	public function actionSync() {
		$session = Yii::$app->session;
		$session->open();
		$client = $this->getClient();
		$tokenSessionKey = 'token-' . $client->prepareScopes();

		if ($session->get($tokenSessionKey)) {
			$client->setAccessToken($session->get($tokenSessionKey));
			$this->redirect($this->redirect);
		}
		// Check to ensure that the access token was successfully acquired.
		if ($client->getAccessToken()) {
			$session->set($tokenSessionKey, $client->getAccessToken());
		}
		else {
			$state = mt_rand();
			$client->setState($state);
			$session->set('state', $state);
			$authUrl = $client->createAuthUrl();
			$this->redirect($authUrl);
		}
	}

	public function getClient() {
		$client = new \Google_Client();
		$client->setClientId($this->OAUTH2_CLIENT_ID);
		$client->setClientSecret($this->OAUTH2_CLIENT_SECRET);
		$client->setScopes('https://www.googleapis.com/auth/youtube');
		$client->setRedirectUri($this->redirect);

		return $client;
	}

	public function actionSuccess() {
		\Yii::$app->view->on(View::EVENT_END_BODY, function () {
			$session = Yii::$app->session;
			$session->open();
			$client = $this->getClient();

			try {
				$tokenSessionKey = 'token-' . $client->prepareScopes();
				if (isset($_GET['code'])) {

					if (strval($session->get('state')) !== strval($_GET['state'])) {
						die('The session state did not match.');
					}

					$client->authenticate($_GET['code']);
					$session->set($tokenSessionKey, $client->getAccessToken());

					header('Location: ' . $this->redirect);
				}

				if (isset($_SESSION[$tokenSessionKey])) {
					$client->setAccessToken($_SESSION[$tokenSessionKey]);

					// Define an object that will be used to make all API requests.
					$youtube = new \Google_Service_YouTube($client);

					// Call the channels.list method to retrieve information about the
					// currently authenticated user's channel.
					$channelsResponse = $youtube->channels->listChannels('contentDetails', [
						'mine' => 'true',
					]);

					foreach ($channelsResponse['items'] as $channel) {
						// Extract the unique playlist ID that identifies the list of videos
						// uploaded to the channel, and then call the playlistItems.list method
						// to retrieve that list.
						$uploadsListId = $channel['contentDetails']['relatedPlaylists']['uploads'];
						$playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', [
							'playlistId' => $uploadsListId,
							'maxResults' => 50,
						]);
						foreach ($playlistItemsResponse['items'] as $playlistItem) {

							$video = new YoutubeVideo();
							$record = $video->findOne(['video_id' => $playlistItem['snippet']['resourceId']['videoId']]);

							if ($record) {
								continue;
							}

							$this->createVideo([
								                   'video_id'      => $playlistItem['snippet']['resourceId']['videoId'],
								                   'title'         => $playlistItem['snippet']['title'],
								                   'channel_id'    => $playlistItem['snippet']['channelId'],
								                   'channel_title' => $playlistItem['snippet']['channelTitle'],
								                   'description'   => $playlistItem['snippet']['description'],
								                   'published_at'  => $playlistItem['snippet']['publishedAt'],
							                   ]);

						}
					}

				}

				Yii::$app->mailer->compose()
				                 ->setFrom('from@domain.com')
				                 ->setTo(Yii::$app->user->identity->email)
				                 ->setSubject('دریافت ویدیوها')
				                 ->setTextBody('دریافت با موفقیت')
				                 ->setHtmlBody('<b>ویدیوهای شما با موفقیت دریافت شد.</b>')
				                 ->send();

			} catch (\Google_Service_Exception $e) {
				var_dump('<p>A service error occurred: <code>%s</code></p>',
				         htmlspecialchars($e->getMessage()));
			} catch (\Google_Exception $e) {
				var_dump('<p>An client error occurred: <code>%s</code></p>',
				         htmlspecialchars($e->getMessage()));
			}
		});

		$searchModel = new YoutubeVideoSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('my-video', [
			'dataProvider' => $dataProvider,
			'message'      => 'انتقال در صف انجام است و بعد از پایان انتقال با ایمیل به شما اطلاع رسانی می شود.',
			'searchModel'  => $searchModel,
		]);
	}

	public function createVideo($item) {
		$video = new YoutubeVideo();
		$video->setAttributes($item);
		$video->owner_id = Yii::$app->user->identity->getId();
		$video->save(FALSE);
	}
}
