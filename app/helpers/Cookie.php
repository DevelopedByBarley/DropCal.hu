<?php
class Coookie
{

  public function setCookie($cookieName, $cookieValue, $expires, $path, $domain = null, $secure = false, $httponly = true): void
  {
    $cookieLevel = isset($_COOKIE["cookie_level"]) ? (int)$_COOKIE["cookie_level"] : 1;
    switch ($cookieLevel) {
      case 1:
        setcookie($cookieName, $cookieValue, $expires, $path, $domain, $secure, $httponly);
        break;
      case 3:
        setcookie($cookieName, $cookieValue, $expires, $path, $domain, $secure, $httponly);
        //ANALYTICS COOKIE SETTINGS
        break;
      case 4:
        setcookie($cookieName, $cookieValue, $expires, $path, $domain, $secure, $httponly);
        //MARKETING COOKIE SETTINGS
        break;
      case 6:
        setcookie($cookieName, $cookieValue, $expires, $path, $domain, $secure, $httponly);
        // FULL COOKIE SETTINGS
        break;

      default:
        "Something went wrong with cookies!";
    }
  }
}
