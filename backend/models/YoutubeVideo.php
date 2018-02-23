<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "youtube_video".
 *
 * @property int $id
 * @property int $owner_id
 * @property string $channel_id
 * @property string $channel_title
 * @property string $video_id
 * @property string $title
 * @property string $description
 * @property int $published_at
 *
 * @property User $owner
 */
class YoutubeVideo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'youtube_video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id', 'channel_id', 'channel_title', 'video_id', 'title', 'description', 'published_at'], 'required'],
            [['owner_id', 'published_at'], 'integer'],
            [['description'], 'string'],
            [['channel_id', 'video_id', 'owner_id'], 'string', 'max' => 255],
            [['channel_title', 'title'], 'string', 'max' => 511],
            [['video_id'], 'unique'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'owner_id' => Yii::t('app', 'Owner ID'),
            'channel_id' => Yii::t('app', 'Channel ID'),
            'channel_title' => Yii::t('app', 'Channel Title'),
            'video_id' => Yii::t('app', 'Video ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'published_at' => Yii::t('app', 'Published At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    /**
     * @inheritdoc
     * @return YoutubeVideoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new YoutubeVideoQuery(get_called_class());
    }
}
