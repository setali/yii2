<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[YoutubeVideo]].
 *
 * @see YoutubeVideo
 */
class YoutubeVideoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return YoutubeVideo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return YoutubeVideo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
