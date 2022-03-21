<?php
namespace app\controllers;



use Yii;
use yii\web\Controller;
use yii\web\Cookie;

use yii\filters\VerbFilter;
use app\models\Form;

use yii\data\Pagination;

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
        $query = Form::find();

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

    public function actionLogin()
    {
        $payload = google_auth\checkJWTGoogle();
        if($payload != E_WARNING)
        {
            Yii::info("[ANNAD] Cookies is set.", __METHOD__);
            return $this->redirect(Yii::$app->homeUrl, 302)->send();
        }

        $client = google_auth\getGoogleClient();
        $urlAuth = $client->createAuthUrl();
        $this->redirect($urlAuth, 302)->send();
    }

    public function actionLogout()
    {
        unset(Yii::$app->response->cookies[getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT')]);
        return $this->redirect(Yii::$app->homeUrl, 302)->send();
    }

    // In GET req. storage auth-code from google side. (code = authGoogleCode)
    public function actionOauthCallback($code = 0)
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

        return $this->redirect(Yii::$app->homeUrl, 302)->send();
    }
}
