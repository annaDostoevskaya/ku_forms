<?php
namespace app\models;
use yii\db\ActiveRecord;

class Answer extends ActiveRecord
{
	public static function tableName()
	{
		return 'answer';
	}
}
?>