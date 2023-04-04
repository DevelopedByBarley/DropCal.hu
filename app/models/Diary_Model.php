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

        $date = isset($body["currentDate"]) ? strtotime($body["currentDate"]) : strtotime("today");
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




















    public function getTemporaryDiaryData()
    {
        $temporaryId = isset($_COOKIE["t_user_id"]) ? $_COOKIE["t_user_id"] : uniqid();
        $t_user = $this->getTemporaryUserById($temporaryId);

        if (!$t_user) {
            $current_time = time();
            $midnight = strtotime('tomorrow midnight');
            $seconds_until_midnight = $midnight - $current_time;
            $date = date('Y-m-d H:i:s', time() + $seconds_until_midnight);

            $this->cookie->setCookie('t_user_id', $temporaryId, time() + $seconds_until_midnight, '/');

            $stmt = $this->pdo->prepare("INSERT INTO `temporary_users` VALUES (:t_userRandomId, NULL, current_timestamp(), :expires);");
            $stmt->bindParam(":t_userRandomId", $temporaryId);
            $stmt->bindParam(":expires", $date);
            $stmt->execute();

            $t_user = $this->getTemporaryUserById($temporaryId);
            $p_diary = $this->getPublicDiary($t_user["t_userId"]);



            if (empty($p_diary)) {
                $this->setInitialTemporaryDiary($t_user["t_userId"]);
                $p_diary = $this->getPublicDiary($t_user["t_userId"]);
            }


            $t_ingredients = $this->getPublicDiaryIngredients($p_diary["p_diaryId"]);
            return [
                "temporary_user" => $t_user,
                "temporary_diary" => $p_diary,
                "temporary_ingredients" => $t_ingredients,
                "minCalorie" => 2000,
                "summaries" => $summaries ?? 0,
            ];
        }

        $p_diary = $this->getPublicDiary($t_user["t_userId"]);
        if (empty($p_diary)) {
            $this->setInitialTemporaryDiary($t_user["t_userId"]);
        }
        $t_ingredients = $this->getPublicDiaryIngredients($p_diary["p_diaryId"]);

        if (!empty($t_ingredients)) {
            $summaries = $this->getSummaries($t_ingredients);
        }

        return [
            "temporary_user" => $t_user,
            "temporary_diary" => $p_diary,
            "temporary_ingredients" => $t_ingredients,
            "summaries" => $summaries ?? 0,
            "minCalorie" => 2000
        ];
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




    private function getTemporaryUserById($temporaryId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `temporary_users` WHERE t_userRandomId = :temporaryId;");
        $stmt->bindParam(":temporaryId", $temporaryId);
        $stmt->execute();
        $t_user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $t_user;
    }

    private function getPublicDiary($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `public_diary` WHERE `t_userRefId` = :userId;");
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        $p_diary = $stmt->fetch(PDO::FETCH_ASSOC);
        return $p_diary;
    }

    private function getPublicDiaryIngredients($diaryId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `public_diary_ingredients` WHERE `p_diaryRefId` = :diaryId;");
        $stmt->bindParam(":diaryId", $diaryId);
        $stmt->execute();
        $p_ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $p_ingredients;
    }

    private function setInitialTemporaryDiary($userId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `public_diary` (`p_diaryId`, `diaryDate`, `t_userRefId`) VALUES (NULL, current_timestamp(), :t_userRefId);");
        $stmt->bindParam(":t_userRefId", $userId);
        $stmt->execute();
    }
}
