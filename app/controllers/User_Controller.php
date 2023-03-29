<?php
require_once 'app/models/User_Model.php';

class UserController
{
    protected $renderer;
    protected $userModel;
    protected $stepModel;
    protected $registrationData;
    public function __construct()
    {

        $this->renderer = new Renderer();
        $this->userModel = new UserModel();
        $this->stepModel = new StepModel();
        $this->registrationData = isset($_COOKIE["registrationData"]) ? json_decode($_COOKIE["registrationData"], true) : "";
    }


    public function registration()
    {
        $this->userModel->register($_FILES);
    }

    public function sendEmailVerification($vars)
    {
        $email = $vars["email"];
        $this->userModel->verificationEmail($email);
    }

    public function emailVerification($vars)
    {       
        $verificationCode = (int)$vars["verificationCode"];
        $this->userModel->verification($verificationCode);
    }
}

class StepsController extends UserController
{
    public function __construct()
    {
        parent::__construct();
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
