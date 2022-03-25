<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

use yii\filters\VerbFilter;

use yii\data\Pagination;

use yii\helpers\Url;

use app\models\Forms;
use app\models\Answers;

use DateTime;
define('SQLITE_DATETIME_FORMAT', 'Y-m-d H:i:s');

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
        $answerQuery = Answers::find();
        
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

        for($i = 0; $i < $_POST['questions_number']; $i++)
        {
            echo $keys[$i] . ' = ' . $values[ $i ] . '<br>';
            $answers[ $keys[$i] ] = $values[ $i ];
        }

        $json_answers = json_encode($answers);

        $answer_db = new Answers();
        $answer_db->form_id = $_POST['idForm'];
        
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
        // NOTE(annad): We use SQLite time by default value for date, CURRENT_TIMESTAMP.
        // TODO(annad): Check it...
        $answer_db->date = $date->format(SQLITE_DATETIME_FORMAT);
        $answer_db->answers = $json_answers;

        $answer_db->save();

        return $this->redirect( 
                Url::toRoute(
                    ['answers/']
                ), 
                302  )->send();
    }


    public function actionShow($id = 0)
    {
        $answer = Answers::find()
                    ->where(['id' => $id])
                    ->one();
        $form = Forms::find()
                    ->where(['id' => $answer->form_id])
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