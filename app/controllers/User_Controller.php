<?php

class UserController
{
    protected $renderer;
    protected $userModel;

    protected $registrationData;
    public function __construct()
    {

        $this->renderer = new Renderer();
        $this->userModel = new UserModel();
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
        $this->userModel->sendVerification($verificationCode);
    }

    public function loginForm()
    {
        session_start();
        if (isset($_COOKIE["userId"])) {
            $_SESSION["userId"] = $_COOKIE["userId"];
        }

        if (isset($_SESSION["userId"])) {
            header("Location: /private/home");
            exit;
        }

        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/subscription/Login_Form.php", [
                "isRegSuccess" => $_GET["isRegSuccess"] ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0
        ]);
    }


    public function userVerification()
    {
        $this->userModel->verification($_POST);
    }

    public function loginVerificationForm($vars)
    {
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/subscription/Login_Verification.php", [
                "userId" => $vars["id"]
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0
        ]);
    }

    public function userLogin($vars)
    {
        $id = $vars["id"];
        $this->userModel->login($_POST, $id);
    }

    public function logoutUser()
    {
        $this->userModel->logout();
    }

}
