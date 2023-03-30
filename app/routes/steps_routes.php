<?php
     require 'app/controllers/Steps_Controller.php';
     $r->addRoute('GET', '/user/registration/{id}', [StepsController::class, 'prevStep']);
     $r->addRoute('POST', '/user/registration/{id}', [StepsController::class, 'nextStep']);
