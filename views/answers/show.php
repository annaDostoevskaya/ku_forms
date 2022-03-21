<?php
use yii\helpers\Html;
?>


<div>
	<h3><?= $form->subject ?></h3>
	<p>
		<h6>Form Author: <?= $form->author_name  ?></h6>
		<h6>Answer Author: <?= $answer->answerer_name ?></h6>
	</p>
	<?php 
		$questions = json_decode($form->questions, $associate = true);
		$answers = json_decode($answer->answer, $associate = true);
		for ($i = 0; $i < $form->questions_count; $i++) {
			echo Html::encode($i + 1) . '. ';
			echo '<label>' . $questions[$i]['content'] . '</label>';
			echo '<p>' . $answers[ $i ] . '</p>';
		} 
	?>
</div>
