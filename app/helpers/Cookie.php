<?php
class Coookie
{

  public function setCookie($cookieName, $cookieValue, $expires, $path, $domain = null, $secure = false, $httponly = true): void
  {

    if (!isset($_COOKIE["cookie_accepted"])) {
      return;
    } else {
      setcookie($cookieName, $cookieValue, $expires, $path, $domain, $secure, $httponly);
    }
  }
}
