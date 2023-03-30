<?php

//GET ROUTES
     $r->addRoute('GET', '/user/registration', [UserController::class, 'registrationStart']);
     $r->addRoute('GET', '/user/login', [UserController::class, 'loginForm']);
     $r->addRoute('GET', '/user/login/verification/{id}', [UserController::class, 'loginVerificationForm']);

     $r->addRoute('GET', '/user/verification/email/send/{email}', [UserController::class, 'sendEmailVerification']);
     $r->addRoute('GET', '/user/verification/email/{verificationCode}', [UserController::class, 'emailVerification']);
     
     //POST ROUTES
     $r->addRoute('POST', '/user/registration', [UserController::class, 'registration']);
     $r->addRoute('POST', '/user/verification', [UserController::class, 'userVerification']);
     $r->addRoute('POST', '/user/login/{id}', [UserController::class, 'userLogin']);
     $r->addRoute('GET', '/user/logout', [UserController::class, 'logoutUser']);
