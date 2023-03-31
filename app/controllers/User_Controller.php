<?php
require_once 'app/helpers/User_Authentication.php';
require_once 'app/helpers/Email_Verification.php';
class UserController
{
    protected $renderer;
    protected $userModel;
    protected $registrationData;
    private $authentication;
    private $emailVerification;
    public function __construct()
    {

        $this->renderer = new Renderer();
        $this->userModel = new UserModel();
        $this->authentication = new Authentication();
        $this->emailVerification = new EmailVerification();
        $this->registrationData = isset($_COOKIE["registrationData"]) ? json_decode($_COOKIE["registrationData"], true) : "";
    }


    public function registration()
    {
        $this->userModel->register($_FILES);
    }

    public function sendVerificationEmail($vars)
    {
        $email = $vars["email"];
        $this->emailVerification->verificationEmail($email);
    }

    public function emailVerification($vars)
    {
        $verificationCode = (int)$vars["verificationCode"];
        $this->emailVerification->sendVerification($verificationCode);
    }

    public function loginForm()
    {
        session_start();
        if (isset($_COOKIE["userId"])) {
            $_SESSION["userId"] = $_COOKIE["userId"];
        }

        if (isset($_SESSION["userId"])) {
            header("Location: /");
            exit;
        }

        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/subscription/Login_Form.php", [
                "isRegSuccess" => $_GET["isRegSuccess"] ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0
        ]);
    }


    public function userAuthentication()
    {
        $this->authentication->authentication($_POST);
    }

    public function authenticationForm($vars)
    {
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/subscription/User_Authentication.php", [
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
