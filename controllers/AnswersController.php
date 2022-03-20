<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

use yii\filters\VerbFilter;

require_once __DIR__ . '/../core/google_auth.php';

use core\google_auth;

class AnswersController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'save' => ['post'],
                ],
            ],
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

    public function actionSave()
    {
        $answers = Array();
        $keys = array_keys($_POST);
        $values = array_values($_POST);

        for($i = 0; $i < $_POST['questions_count']; $i++)
        {
            echo $keys[$i] . ' = ' . $values[ $i ] . '<br>';
            $answers[ $keys[$i] ] = $values[ $i ];
        }

        $json_answers = json_encode($answers);

        $answer_db = new \app\models\Answer();
        $answer_db->id_form = $_POST['idForm'];
        
        $userinfo = google_auth\getGoogleUserInfo();
        $answer_db->answerer_name = $userinfo['username'];
        $answer_db->answerer_email = $userinfo['email'];
        
        $date = new \DateTime('now');
        $answer_db->datetime = $date->format(\DateTime::ATOM);
        $answer_db->answer = $json_answers;

        // $answer_db->save();

        return;
    }


    public function actionShow($id = 0)
    {
    	$answer = \app\models\Answer::find()->where(['id' => $id])->one();
    	return $answer['answer'];
    }
}

?>