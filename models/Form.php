<?php
namespace app\models;
use yii\db\ActiveRecord;

class Form extends ActiveRecord
{
	public static function tableName()
	{
		return 'form';
	}
}
?>