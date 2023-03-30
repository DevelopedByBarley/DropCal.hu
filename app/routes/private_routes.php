<?php
    require_once 'app/controllers/Private_Controller.php';
      $r->addRoute('GET', '/private/home', [PrivateController::class, 'home']);
?>