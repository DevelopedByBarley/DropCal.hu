<?php

    class DiaryController extends UserController {
        private $diaryModel;

        public function __construct()
        {
            parent::__construct();
            $this->diaryModel = new DiaryModel();
        }
        

        public function changeDiaryDate() {
            $this->loginChecker->checkUserIsLoggedInOrRedirect();
            $userId = $_SESSION["userId"] ?? null;

            
            if ($userId) {
                $this->loginChecker->checkUserIsLoggedInOrRedirect();
                $user =  $this->userModel->getUserData();
                $diaryData = $this->diaryModel->getUserDiary($userId, $_POST);
    
                $profileImage = $user["profileImage"];
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
    }
?>