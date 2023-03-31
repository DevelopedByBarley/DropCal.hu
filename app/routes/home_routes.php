<?php
    require_once 'app/controllers/Home_Controller.php';
    $r->addRoute('GET', '/', [HomeController::class, 'getHomePage']);
?>