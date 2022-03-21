<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

use yii\filters\VerbFilter;

use yii\data\Pagination;

use yii\helpers\Url;

use app\models\Answer;
use app\models\Form;

use DateTime;

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

    public function actionIndex()
    {
        $answerQuery = Answer::find();
        
        $pagination = new Pagination(
            [
                'defaultPageSize' => 10,
                'totalCount' => $answerQuery->count(),
            ]
        );

        $answers = $answerQuery->orderBy(['id' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
                'answers' => $answers,
                'pagination' => $pagination,
            ]
        );
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

        $answer_db = new Answer();
        $answer_db->id_form = $_POST['idForm'];
        
        $userinfo = google_auth\getGoogleUserInfo();

        if($userinfo == E_WARNING)
        {
            return $this->redirect( 
                Url::toRoute(
                    ['site/login']
                ), 
                302  )->send();
        }

        $answer_db->answerer_name = $userinfo['username'];
        $answer_db->answerer_email = $userinfo['email'];
        
        $date = new DateTime('now');
        $answer_db->datetime = $date->format(DateTime::ATOM);
        $answer_db->answer = $json_answers;

        $answer_db->save();

        return $this->redirect( 
                Url::toRoute(
                    ['answers/']
                ), 
                302  )->send();
    }


    public function actionShow($id = 0)
    {
        $answer = Answer::find()
                    ->where(['id' => $id])
                    ->one();
        $form = Form::find()
                    ->where(['id' => $answer->id_form])
                    ->one();

        return $this->render('show', 
            [
                'answer' => $answer, 
                'form' => $form
            ]
        );
    }
}

?>