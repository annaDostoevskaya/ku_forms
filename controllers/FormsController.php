<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

require_once __DIR__ . '/../core/google_auth.php';

class FormsController extends Controller
{

    public function behaviors()
    {
        return [

        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

	public function actionIndex($id = 0)
	{
        $payload = checkJWTGoogle();
        if($payload == E_WARNING)
        {
            // TODO(annad): Are you want be Anonymous?..
            return $this->redirect('/index.php?r=site/login', 302)->send();
        }

        if($id = 0) 
        {
            // TODO(annad): I think what we can do?..
            // $id++;
        }

        $query = \app\models\Form::find();
        $form = $query->where(['id' => $id])->one();

        /*
        if($id == 0) {
            return $this->redirect(Yii::$app->homeUrl, 302)->send();
        }

        return $this->render('poll', ['poll_content' => $polls[$id - 1]]);
        */

		return $this->render('index');
	}
}

?>