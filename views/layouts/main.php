<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Html::img(Yii::getAlias('@web') . '/static/images/logo.png',
            [
                'alt' => Yii::$app->name,
                'width' => '40px',
                'height' => '40px',
            ]
        ), 
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            // 'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
            'class' => 'navbar navbar-expand-md navbar-dark fixed-top',
            // NOTE(annad): https://colorscheme.ru/#4E11Tw0w0w0w0
            'style' => 'background-color: #5C2680;'
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            isset(
                Yii::$app->request->cookies[
                    getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT')
                ]
            ) ? (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                . Html::submitButton(
                    'Logout',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            ) : ( 
                ['label' => 'Login with Google', 'url' => ['/site/login', 'future' => Url::current() ]]
            ),

            ['label' => 'Answers', 'url' => ['/answers/index']],
            ['label' => 'Forms', 'url' => ['/site/index']],
        ],
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; Karaganda University named after Academician E.A.Buketov</p>
        <!-- <p class="float-left">&copy; ???????????????????????????? ?????????????????????? ????. ?????????????????? ??.??.????????????????</p> -->
        <!-- <p class="float-right"> <?//=  Yii::powered() ?></p> -->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
