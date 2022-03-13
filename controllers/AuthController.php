<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

use yii\helpers\Console;

define("STDOUT", fopen(Yii::getAlias("@webroot") . "\..\kuforms.log", "w"));

class AuthController extends Controller
{
    public function actionGoogleSignIn()
    {
    	$google_api_key = getenv('GOOGLE-API-KEY');
        return $this->render('google-sign-in', ['google_api_key' => $google_api_key]);
    }

    public function actionGoogleSignInRedirect()
    {
        // TODO(annad): On server we testing it. We MUST rebuild it.
    	Console::stdout("start\n");
    	if (Yii::$app->request->post()) {
    		Console::stdout(Yii::$app->request->post('google_id') . "\n");
    		Console::stdout(Yii::$app->request->post('google_full_name') . "\n");
    		Console::stdout(Yii::$app->request->post('goog_given_name') . "\n");
    		Console::stdout(Yii::$app->request->post('google_family_name') . "\n");
    		Console::stdout(Yii::$app->request->post('google_image_url') . "\n");
    		Console::stdout(Yii::$app->request->post('google_email') . "\n");
    		Console::stdout(Yii::$app->request->post('google_token') . "\n");
    	}

    	return 0;
    }
}

?>