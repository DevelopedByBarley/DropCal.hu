<?php

class UserModel
{
    protected $pdo;
    protected $calculators;
    protected $cookie;
    protected $mailer;
    private $fileSaver;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnect();

        $this->calculators = new Calculators();
        $this->cookie = new Cookie();
        $this->mailer = new Mailer();
        $this->fileSaver = new FileSaver();
    }

    public function register($files)
    {
        $userData = json_decode($_COOKIE["registrationData"], true);
        $profileImage = $this->fileSaver->saver($files["file"], "/profile_images", null);


        $userName = filter_var($userData["userName"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($userData["email"] ?? '', FILTER_SANITIZE_EMAIL);
        $password = password_hash(filter_var($userData["password"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS), PASSWORD_DEFAULT);
        $yearOfBirth = filter_var((int)($userData["yearOfBirth"] ?? ''), FILTER_SANITIZE_NUMBER_INT);
        $sex = filter_var($userData["sex"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $currentWeight = filter_var((int)($userData["currentWeight"] ?? ''), FILTER_SANITIZE_NUMBER_INT);
        $height = filter_var((int)($userData["height"] ?? ''), FILTER_SANITIZE_NUMBER_INT);
        $activity = filter_var($userData["activity"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $allergens = $userData["allergens"] ?? '';
        $diet = filter_var($userData["diet"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $isHaveDiabetes = filter_var($userData["isHaveDiabetes"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $goal = filter_var($userData["goal"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);

        $BMI = filter_var($this->calculators->calculateBMI($currentWeight, $height), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $resultOfBMI = $this->calculators->resultOfBMI($BMI);
        $stateOfBmi =  filter_var($resultOfBMI["state"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $riskOfHealth =  filter_var($resultOfBMI["riskOfHealth"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);


        $calculateBMR = $this->calculators->calculateBMR($sex, $currentWeight, $height, $yearOfBirth, $activity);
        $BMR = filter_var($calculateBMR["BMR"] ?? '',  FILTER_SANITIZE_NUMBER_INT);
        $activityBMR = filter_var($calculateBMR["activityBMR"] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $finalBMR =  filter_var($this->calculators->resultOfGoal($activityBMR, $goal) ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $macros = $this->calculators->getMacro($finalBMR);
        $protein = filter_var($macros["protein"] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $carbohydrate = filter_var($macros["carbohydrate"] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $fat = filter_var($macros["fat"] ?? 0, FILTER_SANITIZE_NUMBER_INT);


        $stmt = $this->pdo->prepare("INSERT INTO `users` VALUES 
        (NULL, 
         :userName, 
         :email, 
         :password, 
         :profileImage,
         :yearOfBirth, 
         :sex, 
         :currentWeight, 
         :height, 
         :activity, 
         :isHaveDiabetes, 
         :goal, 
         :BMI, 
         :stateOfBMI, 
         :riskOfHealth, 
         :BMR, 
         :activityBMR, 
         :finalBMR, 
         :protein, 
         :carbohydrate, 
         :fat, 
         :diet);
         ");


        $stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':profileImage', $profileImage, PDO::PARAM_STR);
        $stmt->bindParam(':yearOfBirth', $yearOfBirth, PDO::PARAM_INT);
        $stmt->bindParam(':sex', $sex, PDO::PARAM_STR);
        $stmt->bindParam(':currentWeight', $currentWeight, PDO::PARAM_INT);
        $stmt->bindParam(':height', $height, PDO::PARAM_INT);
        $stmt->bindParam(':activity', $activity, PDO::PARAM_STR);
        $stmt->bindParam(':isHaveDiabetes', $isHaveDiabetes, PDO::PARAM_STR);
        $stmt->bindParam(':goal', $goal, PDO::PARAM_STR);
        $stmt->bindParam(':BMI', $BMI, PDO::PARAM_STR);
        $stmt->bindParam(':stateOfBMI', $stateOfBmi, PDO::PARAM_STR);
        $stmt->bindParam(':riskOfHealth', $riskOfHealth, PDO::PARAM_STR);
        $stmt->bindParam(':BMR', $BMR, PDO::PARAM_INT);
        $stmt->bindParam(':activityBMR', $activityBMR, PDO::PARAM_INT);
        $stmt->bindParam(':finalBMR', $finalBMR, PDO::PARAM_INT);
        $stmt->bindParam(':protein', $protein, PDO::PARAM_INT);
        $stmt->bindParam(':carbohydrate', $carbohydrate, PDO::PARAM_INT);
        $stmt->bindParam(':fat', $fat, PDO::PARAM_INT);
        $stmt->bindParam(':diet', $diet, PDO::PARAM_STR);
        $stmt->execute();


        $userId = $this->pdo->lastInsertId();

        if ($userId) {

            foreach ($allergens as $allergen) {
                filter_var($allergen, FILTER_SANITIZE_SPECIAL_CHARS);
                
                foreach (ALLERGENS as $allergenListItem) {
                    if ($allergen === $allergenListItem["allergenName"]) {
                        $stmt = $this->pdo->prepare("INSERT INTO `allergens` VALUES (NULL, :allergenName, :allergenNumber, :userId);");
                        $stmt->bindParam(':allergenName', $allergenListItem["allergenName"], PDO::PARAM_STR);
                        $stmt->bindParam(':allergenNumber', $allergenListItem["allergenId"], PDO::PARAM_INT);
                        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            }
        }


        $this->cookie->setCookie('registrationData', '', time() - 3600, '/');
        $this->cookie->setCookie('currentStepId', '', time() - 3600, '/');
        unset($_SESSION["isEmailVerified"]);
        header("Location: /user/login?isRegSuccess=1");
    }


    public function getUserData()
    {
        $userId = $_SESSION["userId"] ?? '';

        if ($userId) {
            $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE userId = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user;
        }
    }



    public function login($body, $id)
    {
        session_start();
        $verificationCode = $_SESSION["verificationCode"];


        if ((int)$body["code"] !== (int)$verificationCode) {
            header('Location: /user/login');
            return;
        }
        unset($_SESSION["verificationCode"]);
        $_SESSION["userId"] = isset($_COOKIE["userId"]) ? $_COOKIE["userId"] : $id;
        $expires = time() + (30 * 24 * 60 * 60);
        if (isset($_SESSION["isRemember"])) {
            $this->cookie->setCookie("userId", $id, $expires, "/");
        }

        header("Location: /user/welcome");
    }

    public function logout()
    {
        session_start();
        session_destroy();

        $cookieParams = session_get_cookie_params();
        setcookie(session_name(), "", 0, $cookieParams["path"], $cookieParams["domain"], $cookieParams["secure"], isset($cookieParams["httponly"]));

        if ($_SERVER['REQUEST_URI'] === '/user/change_profile') {
            header('Location: /user/login');
            return;
        } else {

            header('Location: /');
        }
    }

    public function change()
    {
        $expires = time() - 3600;
        $this->cookie->setCookie("userId", '', $expires, "/");
        $this->logout();
    }
}
