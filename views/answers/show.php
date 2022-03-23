<?php
use yii\helpers\Html;
use yii\helpers\Url;

$questions = json_decode($form->questions, $associate = true);
$answers = json_decode($answer->answer, $associate = true);
?>


<div>
	<div>
		<h3><?= $form->subject ?></h3>
		<div>
			<p>
				<h6>Form Author: <?= Html::encode($form->author_name) ?></h6>
				<h6>Answer Author: <?= Html::encode($answer->answerer_name) ?></h6>
				<h7 class="text-muted">Datetime of answer: <i><?= Html::encode($answer->datetime) ?></i></h7>
			</p>
			<div>
				<a href=
				<?= 
					Url::toRoute(['forms/', 'id' => $form->id]) 
				?>>
					Go To Form!
				</a>
			</div>
		</div>
		<br>
	</div>
	<table class="table table-striped">
		<thread>
			<tr>
				<th scope="col">№</th>
				<th scope="col">Question</th>
				<th scope="col">Answer</th>
			</tr>
		</thread>
		<tbody>
			<?php
				for ($i = 0; $i < $form->questions_count; $i++) {
					echo '<tr>';
						echo '<th ' . 'scope="row"' .'>' . Html::encode($i + 1) .'</th>';
					    echo '<td>' . Html::encode($questions[$i]['content']) . '</td>';
					    echo '<td>' . Html::encode($answers[$i]) . '</td>';
					echo '</tr>';
				} 
			?>
		</tbody>
	</table>
</div>	