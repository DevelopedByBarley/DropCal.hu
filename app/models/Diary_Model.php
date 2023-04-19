<?php
require_once 'app/models/User_Model.php';

class DiaryModel extends UserModel
{
    public function __construct()
    {
        parent::__construct();
    }

    // Public diarie-s lekérése
    public function getDiaryData($userId, $body)
    {

        $date = !empty($body) ? strtotime($body) : strtotime("today");
        $userDiary = $this->getUserDiary($userId, $date) ?? null;

        if (!$userDiary) {
            $this->setUserDiary($userId, $date);
            $userDiary = $this->getUserDiary($userId, $date);
            $diaryIngredients = $this->getDiaryIngredients($userDiary["diaryId"]);
            $summaries = $this->getSummaries($diaryIngredients);
            return [
                "userDiary" => $userDiary,
                "diary_ingredients" => $diaryIngredients,
                "summaries" => $summaries ?? 0,
            ];
        }

        $diaryIngredients = $this->getDiaryIngredients($userDiary["diaryId"]) ?? [];
        $summaries = $this->getSummaries($diaryIngredients);
        return [
            "userDiary" => $userDiary,
            "diary_ingredients" => $diaryIngredients,
            "summaries" => $summaries ?? 0,
        ];
    }


    private function getUserDiaryByDate($date)
    {
    }





    // Lekérjük a diary-t user és dátum alapján
    private function getUserDiary($userId, $date)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `userdiary` WHERE `userRefId` = :userId AND `diaryDate` = :date");
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":date", $date);
        $stmt->execute();
        $userDiary = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userDiary;
    }

    // Beállítjuk a diary-t user és dátum alapján
    private function setUserDiary($userId, $date)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `userdiary` (`diaryId`, `diaryDate`, `userRefId`) VALUES (NULL, :diaryDate, :userRefId);");
        $stmt->bindParam(":diaryDate", $date);
        $stmt->bindParam(":userRefId", $userId);
        $stmt->execute();
    }
    // Lekérjük a hozzávalók listáját diaryId alapján
    private function getDiaryIngredients($diaryId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `diary_ingredients` WHERE `diaryRefId` = :diaryId");
        $stmt->bindParam(":diaryId", $diaryId);
        $stmt->execute();
        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $ingredients;
    }

    private function getSummaries($ingredients)
    {
        $sumOfCalorie = 0;
        $sumOfProtein = 0;
        $sumOfCarb = 0;
        $sumOfFat = 0;

        foreach ($ingredients as $ingredient) {
            $sumOfCalorie += (int)$ingredient["calorie"];
            $sumOfProtein += (int)$ingredient["protein"];
            $sumOfCarb += (int)$ingredient["carb"];
            $sumOfFat += (int)$ingredient["fat"];
        }

        return [
            "sumOfCalorie" => $sumOfCalorie,
            "sumOfProtein" => $sumOfProtein,
            "sumOfCarb" => $sumOfCarb,
            "sumOfFat" => $sumOfFat
        ];
    }
}
