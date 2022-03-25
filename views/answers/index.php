<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'KUForms - Answers';
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
                <i><?= Html::encode("{$answer->date}") ?></i>
                <h6>
                    <div>
                        <p>
                            <label>Answerer: </label>
                            <b><?= Html::encode("{$answer->answerer_name}") ?></b><br>
                            <label>Form subject: <label>
                            <b><?= $answer->getCustomer()->One()->subject ?></b>
                        </p>
                        <h7>
                            <u class="text-muted"><?= $answer->answerer_email ?></u>
                        </h7>
                    </div>
                    <br>
                    <div>
                        <div style="float: left;">
                            <a class="btn btn-primary" href=
                            <?= 
                                Url::toRoute(['answers/show', 'id' => $answer->id]) 
                            ?>>
                                Show Results!
                            </a>
                        </div>
                        <div style="float: right;">
                            <a class="btn btn-primary" href=
                            <?= 
                                Url::toRoute(['forms/', 'id' => $answer->form_id]) 
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