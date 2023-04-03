<?php
require_once 'app/helpers/LoginChecker.php';
require_once 'app/models/Diary_Model.php';
class HomeController
{
    private $userModel;
    private $diaryModel;
    protected $loginChecker;
    private $renderer;
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
        
        if ($userId) {
            $this->loginChecker->checkUserIsLoggedInOrRedirect();
            $user =  $this->userModel->getUserData();
            $diaryData = $this->diaryModel->getUserDiary($userId, null);
            var_dump($diaryData);
        }
        $profileImage = $user["profileImage"] ?? '';
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/Home.php", [
                "user" => $user ?? null,
                "userDiary" => $diaryData ?? null,
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage ?? ""
        ]);
    }
}
