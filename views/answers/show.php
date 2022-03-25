<?php
use yii\helpers\Html;
use yii\helpers\Url;

$questions = json_decode($form->questions, $associate = true);
$answers = json_decode($answer->answers, $associate = true);
?>


<div>
	<div>
		<h3><?= $form->subject ?></h3>
		<div>
			<div class="text-muted">
				<b>Form Author: </b><?= Html::encode($form->author_name) ?><br>
				<b>Answerer: </b><?= Html::encode($answer->answerer_name) ?><br>
				<b>Date: </b><i><?= Html::encode($answer->date) ?></i><br>
			</div><br>
			<div>
				<a class="btn btn-primary" href=
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
				for ($i = 0; $i < $form->questions_number; $i++) 
				{
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