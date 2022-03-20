<?php
use yii\helpers\Html;
?>

<?php
function RendererFrom()
{

}
?>

<?php 

RendererFrom(); // TODO(annad): This should encapsulate the <div>(<form>) below 
$Form_Table = \app\controllers\SiteController::$Form_Table;

?>
<div>
    <h3><?= $Form_Table['subject']; ?></h3>
    <p>
        <h6><?= $Form_Table['author']; ?></h6>
        <h6><?= $Form_Table['author_email']; ?></h6>
    </p>
    <p>
        <h7><?= $Form_Table['date']; ?></h7>
    </p>

    <form action="/index.php?r=site/save-result" method="post" autocomplete="on">
            <?php
                $questions_collection = json_decode($Form_Table['questions'], $associative=true);
                for ($i = 0; $i < $Form_Table['questions_count']; $i++) {
                    $question = $questions_collection[$i];
                    echo '<label ' . 
                         'for='. Html::encode("q" . (string)$i) . 
                         '>'. Html::encode($i + 1) . ' 
                         ' . Html::encode($question['content']) .
                         '</label><br>';

                    echo '<' . Html::encode($question['tag']) . 
                         ' type=' . Html::encode($question['type']) . 
                         ' name=' . Html::encode("q" . (string)$i) . ' autocomplete><br><br>';
                }
            ?>
            <input type="submit">
            <input type="reset"><br>
        <!-- Hidden tag for security CSRF-Attacks. -->
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()); ?><br>
        <input type="hidden" name="id_Form_Table" value=<?= $Form_Table['id'] ?>>
    </form>
</div>