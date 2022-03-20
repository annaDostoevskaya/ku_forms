<?php
namespace app\controllers;



use Yii;
use yii\web\Controller;
use yii\web\Cookie;

// TODO(annad): Find macroses.
    // TODO(annad): i can't find it in documentation or in this 1K+ files.
    // Later we write script for indexing this is files and find it's strings in files. ('https://..etc');
define("GOOGLE_USERINFO_EMAIL", "https://www.googleapis.com/auth/userinfo.email");
define("GOOGLE_USERINFO_PROFILE", "https://www.googleapis.com/auth/userinfo.profile");

define("KUFORMS_ERROR", -1);

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

function checkJWTGoogle()
{
    // TODO(annad): Write checking with https://developers.google.com/identity/sign-in/web/backend-auth
    $cookie_set = isset(Yii::$app->request->cookies[(getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT'))]);
    if(!$cookie_set)
    {
        // TODO(annad): Error handling.
        Yii::info("[KUFORMS_ERROR] Not found cookies.");
        return KUFORMS_ERROR;
    }
    
    $client = getGoogleClient();
    $id_token = Yii::$app->request->cookies->get(getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT'));
    
    // TODO(annad): WTF??! See IT https://github.com/googleapis/google-api-php-client/issues/1630
    // Without it verifyIdToken don't work... I was not looking eleganct solve problem.
    \Firebase\JWT\JWT::$leeway = 60; 

    // TODO(annad): FUCK. I must find method without try/catch.
    try {
        $payload = $client->verifyIdToken($id_token);
    } catch (\UnexpectedValueException $e) {
        // TODO(annad): Error handling.
        Yii::info("[KUFORMS_ERROR] Invalid id_token", __METHOD__);
        return KUFORMS_ERROR;
    }
    
    if(!$payload)
    {
        // TODO(annad): Error handling.
        Yii::info("[KUFORMS_ERROR] Payload error. ", __METHOD__);
        return KUFORMS_ERROR;
    }

    return $payload;
}

class SiteController extends Controller
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
    // echo print_r($Form_Table) . '<br>' . '<br>' . '<br>';
    // $array_2 = json_decode($Form_Table['questions'], $associative=true);
    // echo print_r($array_2['0']['type']);






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
        if($payload != KUFORMS_ERROR)
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
            return KUFORMS_ERROR;
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

    public function actionForm($id = 0)
    {
        $payload = checkJWTGoogle();
        if($payload == KUFORMS_ERROR)
        {
            // TODO(annad): Are you want be Anonymous?..
            return $this->redirect('/index.php?r=site/login', 302)->send();
        }

        if($id = 0) 
        {
            // TODO(annad): I think what we can do?..
            // $id++;
        }

        $Form = SiteController::$Form_Table; // request to db...

        return $this->render('form');

        /*
        if($id == 0) {
            return $this->redirect(Yii::$app->homeUrl, 302)->send();
        }

        return $this->render('poll', ['poll_content' => $polls[$id - 1]]);
        */
    }

    public function actionSaveResult()
    {
        foreach ($_POST as $key => $value) {
            echo $key . ' = ' . $value . '<br>';
        }
    }
}
