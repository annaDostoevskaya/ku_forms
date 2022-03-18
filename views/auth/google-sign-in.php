<?php

use yii\helpers\Html;

?>

<html lang="en">
  <head>
    <title><?= $this->title ?></title>
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content=<?= $google_client_id // TODO(annad): google_client_id can be insert from ENV_VAR. (!!!) ?>>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
  </head>
  <body>
    <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
    <script>

      function redirect(url) {
          // TODO(annad): I'm don't know, KSU use IE9 and < ?..
          var ua        = navigator.userAgent.toLowerCase(),
              isIE      = ua.indexOf('msie') !== -1,
              version   = parseInt(ua.substr(4, 2), 10);

          // Internet Explorer 8 and lower
          if (isIE && version < 9) {
              var link = document.createElement('a');
              link.href = url;
              document.body.appendChild(link);
              link.click();
          }

          // All other browsers can use the standard window.location.href (they don't lose HTTP_REFERER like Internet Explorer 8 & lower does)
          else { 
              window.location.href = url; 
          }
      }

      function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for(let i = 0; i <ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) == ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "";
      }
    </script>
    <script>
      function onSignIn(googleUser) {
        /*
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
        */

        document.cookie = ("__Google_JWToken=" + googleUser.getAuthResponse().id_token);
        // redirect(main_page);
      }
    </script>
  </body>
</html>