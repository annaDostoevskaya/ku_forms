<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'KUForms - ' . $form->subject;
?>

<?php
require_once __DIR__ . '/../../core/form_renderer.php';
use core\form_renderer;
form_renderer\FormRenderer(); // TODO(annad): This should encapsulate the <div>(<form>) below 
?>

<div>
    <h3><?= Html::encode($form->subject); ?></h3>
    <div class="text-muted">
        <h7>
            <b>Description:</b><br>
            <?= Html::encode($form->description); ?>
        </h7>
        <h6>
            &copy; <?= Html::encode($form->author_name); ?><br>
            <u><?= Html::encode($form->author_email); ?></u>
        </h6>
        <small>Date: <b><i><?= Html::encode($form->date); ?></i></b></small>
    </div>
    <br>
    <form action="<?= Url::to(['answers/save']) ?>"
          method="post" 
          autocomplete="on">
          <div>
            <?php
                $questions_collection = json_decode($form->questions, $associate = true);
                for ($i = 0; $i < $form->questions_number; $i++) {
                    $question = $questions_collection[$i];
                    echo '<div class="form-group">';
                    echo '<label ' . 
                         'for='. Html::encode($i) . 
                         '>' . Html::encode($i + 1) . 
                         '. ' . Html::encode($question['content']) .
                         '</label>'.
                         '<br>';
                    echo '<' . Html::encode($question['tag']) . 
                         ' type=' . Html::encode($question['type']) . 
                         ' name=' . Html::encode($i) . 
                         ' class="form-control"'. // TODO(annad): It's add if in JSON not ve 
                         '>';                     // defined this field.
                    echo '</div>';
                    // echo '<br>';
                }
            ?>
            <input type="submit" class="btn btn-primary">
            <input type="reset" class="btn btn-primary"><br>
        <!-- Hidden tag for security CSRF-Attacks. -->
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()); ?><br>
        <input  type="hidden" 
                name="questions_number"
                value=<?= $form->questions_number; ?>>
        <input  type="hidden" 
                name="idForm"
                value=<?= $form->id; ?>>
          </div>
    </form>
</div>