<?php
    require_once 'app/controllers/Public_Controller.php';
    $r->addRoute('GET', '/', [PublicController::class, 'getHomePage']);
?>