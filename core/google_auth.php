<?php
namespace core\google_auth;

use Yii;
use Google;
use Google_Client;

// NOTE(annad): write an independent platform layer(?)

function getGoogleClient()
{
    /*
    TODO(annad):
    For more security: https://developers.google.com/identity/protocols/oauth2/web-server#:~:text=client%2D%3EsetAccessType(%27offline%27)%3B-,state,%24client%2D%3EsetState(%24sample_passthrough_value)%3B,-include_granted_scopes
    */

    $client = new Google_Client();
    $client->setApplicationName(getenv('GOOGLE-APP-NAME'));
    $client->setScopes(
        [
            Google\Service\Oauth2::USERINFO_EMAIL, 
            Google\Service\Oauth2::USERINFO_PROFILE
        ]
    );
    // $client->setAuthConfig(__DIR__ . "/../" . getenv('PATH-TO-CREDENTIALS-JSON'));
    $client->setClientId(getenv('GOOGLE-CLIENT-ID'));
    $client->setClientSecret(getenv('GOOGLE-CLIENT-SECRET'));
    $client->setRedirectUri(getenv('GOOGLE-REDIRECT-URI'));

    $client->setAccessType('offline');

    return $client;
}

function checkJWTGoogle()
{
    $cookieIsSet = isset(Yii::$app->request->cookies[(getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT'))]);
    if(!$cookieIsSet)
    {
        // TODO(annad): Error handling.
        Yii::info("[KUFORMS_ERROR] Not found cookies.");
        return E_WARNING;
    }
    
    $client = getGoogleClient();
    $idToken = Yii::$app->request->cookies->get(getenv('GOOGLE-NAME-COOKIE-STORAGING-JWT'));
    
    // TODO(annad): See IT https://github.com/googleapis/google-api-php-client/issues/1630
    // Without it verifyIdToken don't work... I was not looking eleganct solve problem.             // write time.
        // NOTE(annad): I did't see any other options than to leave this line, 
        // maybe we should check how it will work on the deployment, but we will check this later.  // refactoring 1. 03/20/2022 8:56PM.
    \Firebase\JWT\JWT::$leeway = 60;

    // NOTE(annad): I must find method without try/catch. 
    // TODO <- NOTE: refactoring 1. 03/20/2022 8:56PM.
    try {
        $payload = $client->verifyIdToken($idToken);
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

    if($payload['aud'] != $client->getClientId())
    {
        // TODO(annad): Error handling.
        Yii::info("[KUFORMS_ERROR] aud and app-identifyer not equal! ", __METHOD__);
        return E_WARNING;
    }

    return $payload;
}

function getGoogleUserInfo()
{
    // TODO(annad): It's wrong!!! We must get data from ACCESS TOKEN
    // From Google_Service_Auth client! ->userinfo->get()!!!
    // https://developers.google.com/identity/sign-in/web/backend-auth
    // https://developers.google.com/docs/api/quickstart/php
    // stab.
    $payload = checkJWTGoogle(); 

    if($payload == E_WARNING)
    {
        return E_WARNING;
    }

    return [
        'username' => $payload['name'],
        'email' => $payload['email']
    ];
}
?>