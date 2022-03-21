<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div>
	<h3>Answers</h3>
	<div>
		<ul class="list-group">
		<?php foreach ($answers as $answer): ?>
			<li class="list-group-item">
				<label><h6><b>
					<?= $answer->id ?>.
				</b></h6></label>
				<i><?= Html::encode("{$answer->datetime}") ?></i>
				<h6>
					<div>
						<!-- TODO(annad): We must show subject form! -->
						<label>Answerer:</label>
						<b><?= Html::encode("{$answer->answerer_name}") ?></b><br>
						<h7><u style="opacity: .7;"><?= $answer->answerer_email ?></u></h7><br>
					</div>
					<div>
						<div style="float: left;">
							<a href=
							<?= 
								Url::toRoute(['answers/show', 'id' => $answer->id]) 
							?>>
								Show Results!
							</a>
						</div>
						<div style="float: right;">
							<a href=
							<?= 
								Url::toRoute(['forms/', 'id' => $answer->id_form]) 
							?>>
								Go to Form!
							</a>
						</div>
					</div>
				</h6>
			</li>
			<br>
		<?php endforeach; ?>
		</ul>
	</div>
	<?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>