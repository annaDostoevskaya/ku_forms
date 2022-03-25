<?php
namespace app\models;
use yii\db\ActiveRecord;

class Answers extends ActiveRecord
{
	public static function tableName()
	{
		return 'answers';
	}

	public function getCustomer()
	{
		return $this->hasOne(
			Forms::class, [
				'id' => 'form_id',
			]
		);
	}
}
?>