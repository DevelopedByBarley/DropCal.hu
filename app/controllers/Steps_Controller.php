<?php
require 'app/controllers/User_Controller.php';
require 'app/models/Steps_Model.php';

class StepsController extends UserController
{
    private $stepModel;
    public function __construct()
    {
        parent::__construct();
        $this->stepModel = new StepModel();
    }

    public function prevStep($vars)
    {
        $currentStepPage = $this->stepModel->prevStep($vars);
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/subscription/Registration/$currentStepPage", [
                "registrationData" => $this->registrationData
            ]),
            "currentStepId" => $_COOKIE["currentStepId"] ?? 0
        ]);
    }

    public function nextStep($vars)
    {
        $currentStepPage = $this->stepModel->nextStep($vars, $_POST);
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/subscription/Registration/$currentStepPage", [
                "registrationData" => $this->registrationData,
                "isVerificationFail" => $_GET["isVerificationFail"] ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0
        ]);
    }
}
