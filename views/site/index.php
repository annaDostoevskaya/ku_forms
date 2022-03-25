<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'KUForms - Main';
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
				<i><?= Html::encode("{$form->date}") ?></i>
				<h6>
					<div>
						<label>Form Author:</label>
						<b><?= Html::encode("{$form->author_name}") ?></b><br>
						<h7><u class="text-muted"><?= $form->author_email ?></u></h7><br>
					</div>
					<div>
						<div style="float: right;">
							<a class="btn btn-primary" href=
							<?= 
								Url::to(['forms/', 'id' => $form->id])
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
    <div >
        <?= 
            LinkPager::widget([
                'pagination' => $pagination,
                'linkOptions' => [
                    'class' => 'page-link'
                ],
                'disabledPageCssClass' => 'page-link',
                // centring it....
                // change >> on Previous or any.
            ]) 
        ?>
    </div>
</div>