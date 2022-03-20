<?php
namespace app\controllers;



use Yii;
use yii\web\Controller;
use yii\web\Cookie;

require_once __DIR__ . '/../core/google_auth.php';

class SiteController extends Controller
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $payload = checkJWTGoogle();
        if($payload != E_WARNING)
        {
            Yii::info("[ANNAD] Cookies is set.", __METHOD__);
            return $this->redirect(Yii::$app->homeUrl, 302)->send();
        }

        $client = getGoogleClient();
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

        $client = getGoogleClient();
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);
        $id_token = $accessToken['id_token'];

        $cookies = Yii::$app->response->cookies;
        
        $cookie_token_id = new Cookie(
            ['name' => getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT'), 
            'value' =>  $id_token, 'httpOnly' => true, 'secure' => true]
        );
        
        $cookies->add($cookie_token_id);

        return $this->redirect(Yii::$app->homeUrl, 302)->send();
    }
}
