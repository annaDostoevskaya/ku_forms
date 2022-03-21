<?php
use yii\helpers\Html;
?>

<?php
require_once __DIR__ . '/../../core/form_renderer.php';
use core\form_renderer;
form_renderer\FormRenderer(); // TODO(annad): This should encapsulate the <div>(<form>) below 
?>

<div>
    <h3><?= $form->subject; ?></h3>
    <p>
        <h6><?= $form->author_name; ?></h6>
        <h6><?= $form->author_email; ?></h6>
    </p>
    <p>
        <h7><?= $form->datetime; ?></h7>
    </p>
    <!-- action link must be get from application function. If url manager change it? -->
    <form action="/index.php?r=answers/save"
          method="post" 
          autocomplete="on">
            <?php
                $questions_collection = json_decode($form->questions, $associate = true);
                for ($i = 0; $i < $form->questions_count; $i++) {
                    $question = $questions_collection[$i];
                    echo '<label ' . 
                         'for='. Html::encode($i) . 
                         '>' . Html::encode($i + 1) . 
                         '. ' . Html::encode($question['content']) .
                         '</label>'.
                         '<br>';

                    echo '<' . Html::encode($question['tag']) . 
                         ' type=' . Html::encode($question['type']) . 
                         ' name=' . Html::encode($i) . '>'.
                         '<br><br>';
                }
            ?>
            <input type="submit">
            <input type="reset"><br>
        <!-- Hidden tag for security CSRF-Attacks. -->
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()); ?><br>
        <input  type="hidden" 
                name="questions_count"
                value=<?= $form->questions_count; ?>>
        <input  type="hidden" 
                name="idForm"
                value=<?= $form->id; ?>>
    </form>
</div>