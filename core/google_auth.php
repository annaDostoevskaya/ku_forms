<?php
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

function checkJWTGoogle()
{
    // TODO(annad): Write checking with https://developers.google.com/identity/sign-in/web/backend-auth
    $cookie_set = isset(Yii::$app->request->cookies[(getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT'))]);
    if(!$cookie_set)
    {
        // TODO(annad): Error handling.
        Yii::info("[KUFORMS_ERROR] Not found cookies.");
        return E_WARNING;
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
        return E_WARNING;
    }
    
    if(!$payload)
    {
        // TODO(annad): Error handling.
        Yii::info("[KUFORMS_ERROR] Payload error. ", __METHOD__);
        return E_WARNING;
    }

    return $payload;
}

// TODO(annad): Write Implementaion. It's just stab.
function getGoogleUserInfo()
{
    return [
        'username' => 'Anna Dostoevskaya', 
        'email' => 'iwantknow.aboutjt68h43@gmail.com'
    ];
}
?>