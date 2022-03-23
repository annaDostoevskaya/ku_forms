<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div>
	<h3>Forms</h3>
	<div>
		<ul class="list-group">
		<?php foreach ($forms as $form): ?>
			<li class="list-group-item">
				<label>
					<h6>
						<b>
							<?= $form->id  ?>.
							<?= Html::encode("{$form->subject}") ?>
						</b>
					</h6>
				</label><br>
				<i><?= Html::encode("{$form->datetime}") ?></i>
				<h6>
					<div>
						<!-- TODO(annad): We must show subject form! -->
						<label>Form Author:</label>
						<b><?= Html::encode("{$form->author_name}") ?></b><br>
						<h7><u class="text-muted"><?= $form->author_email ?></u></h7><br>
					</div>
					<div>
						<div style="float: right;">
							<a href=
							<?= 
								Url::toRoute(['forms/', 'id' => $form->id]) 
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