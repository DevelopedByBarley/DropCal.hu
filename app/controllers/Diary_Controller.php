<?php

class DiaryController
{

    private $diaryModel;
    private $loginChecker;
    private $renderer;
    private $userModel;
    public function __construct()
    {

        $this->diaryModel = new DiaryModel();
        $this->loginChecker = new LoginChecker();
        $this->renderer = new Renderer();
        $this->userModel = new UserModel();
    }


    public function getDiaryByDate()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();

        $body = $_GET["date"];
        $userId = $_SESSION["userId"] ?? null;
        $diaryData = $this->diaryModel->getDiaryData($userId, $body);

        $user =  $this->userModel->getUserData();
        $profileImage = $user["profileImage"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/user/diary/User_Diary.php", [
                "user" => $user,
                "diaryData" => $diaryData
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage ?? ""
        ]);
    }
}
