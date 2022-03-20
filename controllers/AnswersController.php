<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

require_once __DIR__ . '/../core/google_auth.php';

class AnswersController extends Controller
{

    public static $Form_Table = [
        'id' => 0,
        'date' => '03/20/2022',
        'author' => 'Anna',
        'author_email' => 'me@example.com',

        'subject' => 'My First Forms',
        'questions_count' => 3,

        // misc...

            // ...
            // This data storage in SQL table as NoSQL data. In JSON format and
            // later we decode it.
        'questions' => '{
            "0" : {
                "tag" : "input",
                "type" : "text",
                "options" : {
                    "required" : "true"
                },
                "content" : "What\' your name?"
            },

            "1" : {
                "tag" : "input",
                "type" : "date",
                "content" : "How old are you?"
            },
            
            "2" : {
                "tag" : "input",
                "type" : "email",
                "content" : "please, give your e-mail:"
            }
        }'

    ];

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

    public function actionSave($id = 0)
    {
        // if($id == 0) {
        //     return $this->redirect(Yii::$app->homeUrl, 302)->send();
        // }
        $answers = Array();
        $keys = array_keys($_POST);
        $values = array_values($_POST);

        // TODO(annad): Add ignored keys in $_POST.
        
        // TODO(annad): This check does not make sense, 
        // you need to make sure that access to this part 
        // of the site is only possible from the form.
        if (count($_POST) >= AnswersController::$Form_Table['questions_count'])
        {
            for($i = 0; $i < AnswersController::$Form_Table['questions_count']; $i++)
            {
            	echo $keys[$i] . ' = ' . $values[ $i ] . '<br>';
                $answers[ $keys[$i] ] = $values[ $i ];
            }
        } else {
            Yii::info('[KUFORMS_ERROR] Size $_POST[] smaller qeustions.', __METHOD__);
            return E_WARNING;
        }

        $json_answers = json_encode($answers);

        $answer_db = new \app\models\Answer();
        $answer_db->id_form = $id;
        
        $userinfo = \getGoogleUserInfo();
        $answer_db->answerer_name = $userinfo['username'];
        $answer_db->answerer_email = $userinfo['email'];
        
        $date = new \DateTime('now');
        $answer_db->datetime = $date->format(\DateTime::ATOM);
        $answer_db->answer = $json_answers;

        // $answer_db->save();

        return;
        // return $json_answers;
    }


    public function actionShow($id = 0)
    {
    	$answer = \app\models\Answer::find()->where(['id' => $id])->one();
    	return $answer['answer'];
    }
}

?>