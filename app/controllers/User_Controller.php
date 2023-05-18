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
    private $loginChecker;
    public function __construct()
    {

        $this->renderer = new Renderer();
        $this->userModel = new UserModel();
        $this->authentication = new Authentication();
        $this->emailVerification = new EmailVerification();
        $this->loginChecker = new LoginChecker();
        $this->registrationData = isset($_COOKIE["registrationData"]) ? json_decode($_COOKIE["registrationData"], true) : $_POST;
    }

    public function userProfile()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $user =  $this->userModel->getUserData();
        $profileImage = $user["profileImage"];
        $userName = $user["userName"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/user/profile/Profile.php", [
                "isSuccess" => $_GET["isSuccess"] ?? null,
                "recipes" => $recipes ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage
        ]);
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
            "content" => $this->renderer->render("/pages/public/user/subscription/Login_Form.php", [
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
            "content" => $this->renderer->render("/pages/public/user/subscription/User_Authentication.php", [
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

    public function changeProfile()
    {

        $this->userModel->change();
    }

    public function renderWelcomePage()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $user = $this->userModel->getUserData();
        $userName = $user["userName"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/Welcome.php", [
                "userName" => $userName
            ])
        ]);
    }
}
