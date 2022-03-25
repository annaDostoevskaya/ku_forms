<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

use app\models\Forms;

require_once __DIR__ . '/../core/google_auth.php';

use core\google_auth;

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
            ]
        ];
    }

	public function actionIndex($id = 0)
	{
        $payload = google_auth\checkJWTGoogle();
        if($payload == E_WARNING)
        {
            // TODO(annad): Are you want be Anonymous?..
            $future = Url::to([
                Url::current()
            ]);

            $urlTo = Url::to([
                'site/login', 
                'future' => $future
            ]);

            return $this->redirect($urlTo, 302)->send();
        }

        $query = Forms::find();
        $form = $query->where(['id' => $id])->one();
        
		return $this->render('index', ['form' => $form]);
	}
}
?>