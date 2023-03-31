<?php
     require 'app/helpers/Step.php';
     $r->addRoute('GET', '/user/registration/{id}', [Step::class, 'prevStep']);
     $r->addRoute('POST', '/user/registration/{id}', [Step::class, 'nextStep']);
