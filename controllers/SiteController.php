<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\Url;

use app\models\Forms;

require_once __DIR__ . '/../core/google_auth.php';

use core\google_auth;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
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
        // $answer = Answers::findOne(0); TODO(annad): use it (!!!);
        $query = Forms::find();

        $pagination = new Pagination(
            [
                'defaultPageSize' => 5,
                'totalCount' => $query->count(),
            ]
        );

        $forms = $query->orderBy(['id' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
                'forms' => $forms, 
                'pagination' => $pagination,
            ]
        );
    }

    public function actionLogin($future = '')
    {
        $payload = google_auth\checkJWTGoogle();
        if($payload != E_WARNING)
        {
            Yii::info("[ANNAD] Cookies is set.", __METHOD__);
            return $this->redirect(Url::home(), 302)->send();
        }

        $client = google_auth\getGoogleClient();
        if($future != '')
        {
            $client->setState($future);
        }

        $urlAuth = $client->createAuthUrl();
        $this->redirect($urlAuth, 302)->send();
    }

    public function actionLogout()
    {
        unset(Yii::$app->response->cookies[getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT')]);
        return $this->redirect(Url::home(), 302)->send();
    }

    // In GET req. storage auth-code from google side. (code = authGoogleCode)
    public function actionOauthCallback($code = 0, $state = '')
    {
        if(!$code) {
            // TODO(annad): Error handling.
            return E_WARNING;
        }

        $client = google_auth\getGoogleClient();
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);
        $idToken = $accessToken['id_token'];

        $cookies = Yii::$app->response->cookies;
        $cookieIdToken = new Cookie(
            ['name' => getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT'), 
            'value' =>  $idToken, 'httpOnly' => true, 'secure' => true]
        );
        
        $cookies->add($cookieIdToken);

        if($state != '')
        {
            return $this->redirect(Url::to([$state]), 302)->send();
        }
        return $this->redirect(Url::home(), 302)->send();
    }
}
