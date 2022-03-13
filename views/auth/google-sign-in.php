<?php

use yii\helpers\Html;

?>

<html lang="en">
  <head>
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content=<?= $google_client_id?>>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
  </head>
  <body>
    <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
    <script>
      function onSignIn(googleUser) {
        var profile = googleUser.getBasicProfile();

        $.post("/index.php?r=auth/google-sign-in-redirect", 
            {
              // TODO(annad): It is just testing api. We must rebuild it.
              // Useful data for your client-side scripts:
              "google_id" : profile.getId(), // Don't send this directly to your server!
              "google_full_name" : profile.getName(),
              "goog_given_name" : profile.getGivenName(),
              "google_family_name" : profile.getFamilyName(),
              "google_image_url" : profile.getImageUrl(),
              "google_email" : profile.getEmail(),
              "google_token" : googleUser.getAuthResponse().id_token, // The ID token you need to pass to your backend:
            }
          );

        document.cookie = ("google_token=" + googleUser.getAuthResponse().id_token);
      }
    </script>
  </body>
</html>