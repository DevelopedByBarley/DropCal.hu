<?php

require 'app/models/User_Model.php';

class DiaryModel extends UserModel
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserDiary($id, $body)
    {
        $date = isset($body["date"]) ? strtotime($body["date"]) : strtotime("today");
        $userDiary = $this->getUserDiarySingle($date, $id);
        $sumOfCalories = 0;

        if (!$userDiary) {
            $userDiary = $this->setUserDiary($date, $id);
            $userDiaryIngredients = $this->getUserDiaryIngredients($userDiary["diaryId"]);
            return [
                "userDiary" => $userDiary,
                "diaryIngredients" => $userDiaryIngredients
            ];
        }


        $userDiaryIngredients = $this->getUserDiaryIngredients($userDiary["diaryId"]);
    
        if(!empty($userDiaryIngredients)) {
            foreach($userDiaryIngredients as $ingredient) {
                $sumOfCalories += (int)$ingredient["calorie"];
            }
        }

        return [
            "userDiary" => $userDiary,
            "diaryIngredients" => $userDiaryIngredients,
            "sumOfCalories" => $sumOfCalories
        ];
    }

    private function getUserDiarySingle($date, $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `userdiary` WHERE diaryDate = :diaryDate AND userRefId = :id");
        $stmt->bindParam(":diaryDate", $date);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $userDiary = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userDiary;
    }

    private function getUserDiaryIngredients($diaryId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `diary_ingredients` WHERE `diaryRefId` = :diaryId");
        $stmt->bindParam(":diaryId", $diaryId);
        $stmt->execute();
        $diaryIngredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $diaryIngredients;
    }

    private function setUserDiary($date, $diaryId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `userdiary` (`diaryId`, `diaryDate`, `userRefId`) VALUES (NULL, :diaryDate, :userRefId);");
        $stmt->bindParam(":diaryDate", $date);
        $stmt->bindParam(":userRefId", $diaryId);
        $stmt->execute();
        if ($this->pdo->lastInsertId()) {
            $userDiary =  $this->getUserDiarySingle($date, $diaryId);
            return $userDiary;
        }
    }
}
