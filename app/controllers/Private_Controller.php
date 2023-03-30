<?php

require 'app/models/Private_Model.php';

class PrivateController extends UserController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {
        $this->userModel->checkUserIsLoggedInOrRedirect();

        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/Home.php", []),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0
        ]);
    }
}
