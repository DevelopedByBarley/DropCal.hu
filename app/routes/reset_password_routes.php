<?php
require_once 'app/helpers/ResetPw.php';
$r->addRoute('GET', '/forgot_password', [ResetPw::class, 'forgotPwForm']);
$r->addRoute('GET', '/reset_pw', [ResetPw::class, 'resetPwForm']);
$r->addRoute('POST', '/pw_request', [ResetPw::class, 'newPwRequest']);
$r->addRoute('POST', '/set_new_password', [ResetPw::class, 'setNewPw']);
