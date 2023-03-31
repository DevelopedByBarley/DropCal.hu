<?php
require_once 'app/helpers/LoginChecker.php';
class HomeController
{
    private $userModel;
    private $loginChecker;
    private $renderer;
    public function __construct()
    {
        $this->renderer = new Renderer();
        $this->loginChecker = new LoginChecker();
        $this->userModel = new UserModel();
    }

    public function getHomePage()
    {
        session_start();
        $userId = $_SESSION["userId"] ?? null;

        if (!$userId) {
            $this->renderPublicPage();
        } else {
            $this->renderPrivatePage($userId);
        }
    }

    private function renderPublicPage()
    {
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/public/Home.php", []),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
        ]);
    }

    private function renderPrivatePage($userId)
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $user =  $this->userModel->getUserData();
        $profileImage = $user["profileImage"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/Home.php", [
                "user" => $user
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage ?? ""
        ]);
    }
}
