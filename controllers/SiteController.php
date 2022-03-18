<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

use yii\web\Cookie;
// Yii::$app->request->cookies->readOnly = false;

use yii\helpers\Console;
define("STDOUT", fopen(Yii::getAlias("@webroot") . "\..\kuforms.log", "w"));


// TODO(annad): Find macroses.
    // TODO(annad): i can't find it in documentation or in this 1K+ files.
    // Later we write script for indexing this is files and find it's strings in files. ('https://..etc');
define("GOOGLE_USERINFO_EMAIL", "https://www.googleapis.com/auth/userinfo.email");
define("GOOGLE_USERINFO_PROFILE", "https://www.googleapis.com/auth/userinfo.profile");

function getGoogleClient()
{
    /*
    TODO(annad):
    For more security: https://developers.google.com/identity/protocols/oauth2/web-server#:~:text=client%2D%3EsetAccessType(%27offline%27)%3B-,state,%24client%2D%3EsetState(%24sample_passthrough_value)%3B,-include_granted_scopes
    */

    $client = new \Google_Client();
    $client->setApplicationName(getenv('APP-NAME'));
    $client->setScopes([GOOGLE_USERINFO_EMAIL, GOOGLE_USERINFO_PROFILE]);
    $client->setAuthConfig(__DIR__ . "/../" . getenv('PATH-TO-CREDENTIALS-JSON'));
    $client->setRedirectUri(getenv('GOOGLE-REDIRECT-URI'));

    return $client;


}



class SiteController extends Controller
{
/*

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

*/

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPoll($id = 0)
    {

        $polls = [
            ["Как вас зовут?", "Сколько вам лет?"],
        ];

        if($id == 0) {
            return 0;
        }

        return $this->render('poll', ['poll_content' => $polls[$id - 1]]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionLogin()
    {
        $client = getGoogleClient();
        $urlAuth = $client->createAuthUrl();
        return $this->render('login', ['url_auth' => $urlAuth]);
    }

    // In GET req. storage auth-code from google side. (code = authGoogleCode)
    public function actionOauthCallback($code = 0)
    {
        // TODO(annad ): WTF??! See IT https://github.com/googleapis/google-api-php-client/issues/1630
        // Without it verifyIdToken don't work... I was not looking eleganct solve problem.
        \Firebase\JWT\JWT::$leeway = 60; 


        if(!$code) {
            // TODO(annad): Error handling.
            return 0;
        }

        $client = getGoogleClient();
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);
        $id_token = $accessToken['id_token'];

        $cookies = Yii::$app->response->cookies;
        $cookie_token_id = new Cookie(['name' => getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT'), 'value' =>  $id_token]);
        $cookies->add($cookie_token_id);

        return $this->redirect([ '/' ]);
    }

}
