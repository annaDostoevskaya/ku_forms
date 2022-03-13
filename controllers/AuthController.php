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
    	$google_client_id = getenv('GOOGLE-CLIENT-ID');
        return $this->render('google-sign-in', ['google_client_id' => $google_client_id]);
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

    private function _googleCheckToken()
    {
        // https://oauth2.googleapis.com/tokeninfo?id_token=123456789ABCDYFG
    }
}

?>