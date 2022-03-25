<?php
namespace app\models;
use yii\db\ActiveRecord;

class Forms extends ActiveRecord
{
	public static function tableName()
	{
		return 'forms';
	}

	public function getOrders()
	{
		return $this->hasMany(
			Answers::class, [
				'form_id' => 'id',
			],
		);
	}
}
?>