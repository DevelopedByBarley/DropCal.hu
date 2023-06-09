<?php

//GET ROUTES
     $r->addRoute('GET', '/user/registration', [UserController::class, 'registrationStart']);
     $r->addRoute('GET', '/user/login', [UserController::class, 'loginForm']);
     $r->addRoute('GET', '/user/welcome', [UserController::class, 'renderWelcomePage']);
     $r->addRoute('GET', '/user/logout', [UserController::class, 'logoutUser']);
     $r->addRoute('GET', '/user/profile', [UserController::class, 'userProfile']);
     $r->addRoute('GET', '/user/profile/update', [UserController::class, 'updateProfileForm']);
     $r->addRoute('GET', '/user/change_profile', [UserController::class, 'changeProfile']);
     $r->addRoute('GET', '/user/login/authentication/{id}', [UserController::class, 'authenticationForm']);
     
     //EMAIL VERIFICATION
     $r->addRoute('GET', '/user/verification/email/send/{email}', [UserController::class, 'sendVerificationEmail']);
     $r->addRoute('GET', '/user/verification/email/{verificationCode}', [UserController::class, 'emailVerification']);
     
     //POST ROUTES
     $r->addRoute('POST', '/user/registration', [UserController::class, 'registration']);
     $r->addRoute('POST', '/user/authentication', [UserController::class, 'userAuthentication']);
     $r->addRoute('POST', '/user/login/{id}', [UserController::class, 'userLogin']);
     
