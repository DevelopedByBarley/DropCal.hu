<?php
require_once 'app/helpers/LoginChecker.php';
require_once 'app/models/Diary_Model.php';
class HomeController
{
    private $userModel;
    private $loginChecker;
    private $renderer;
    private $diaryModel;
    public function __construct()
    {
        $this->renderer = new Renderer();
        $this->loginChecker = new LoginChecker();
        $this->userModel = new UserModel();
        $this->diaryModel = new DiaryModel();
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
            "content" => $this->renderer->render("/pages/public/Home.php", [
                "user" => null,
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
        ]);
    }


    public function renderPrivatePage($userId)
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $user =  $this->userModel->getUserData();
        $diary_data = $this->diaryModel->getDiaryData($user["userId"], isset($_POST) ? $_POST : null);
        $profileImage = $user["profileImage"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/Home.php", [
                "user" => $user,
                "diaryData" => $diary_data
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage ?? ""
        ]);
    }
}
