<?php

class UserModel
{
    protected $pdo;
    protected $calculators;
    protected $cookie;
    private $mailer;
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


    public function verificationEmail($email)
    {

        session_start();
        $verificationCode = rand(1000, 9999);
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $emailBody = "";

        $_SESSION["emailVerificationCode"] = $verificationCode;

        if (!$user) {
            $emailBody .= "
            <h1>A te email hitelesítő kódód:</h1>
            <h3>$verificationCode</h3> 
        ";
        } else {
            $emailBody .= "
            <h1>Ezen az email címen már egyszer regisztráltál , vagy valaki megpróbált a te adataiddal regisztrálni!</h1>
            <p>Kérlek ezen a linken <a href=\"#\">link</a> próbálj meg belépni!</p> 
        ";
        }

        $this->mailer->send($email, $emailBody);
    }

    public function sendVerification($verificationCode)
    {
        session_start();
        $isVerified = $verificationCode === (int)$_SESSION["emailVerificationCode"];
        if (!$isVerified) {
            echo json_encode(
                ["state" => false]
            );
            return;
        };

        $_SESSION["isEmailVerified"] = true;

        echo json_encode(
            ["state" => true]
        );
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
                filter_var($allergen ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
                $stmt = $this->pdo->prepare("INSERT INTO `allergens` VALUES (NULL, :allergenName, :userId);");
                $stmt->bindParam(':allergenName', $allergen, PDO::PARAM_STR);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        $this->cookie->setCookie('registrationData', '', time() - 3600, '/');
        $this->cookie->setCookie('currentStepId', '', time() - 3600, '/');
        unset($_SESSION["isEmailVerified"]);
        header("Location: /user/login?isRegSuccess=1");
    }


    public function verification($body)
    {
        session_start();
        $email = filter_var($body["email"] ?? '', FILTER_SANITIZE_EMAIL);
        $password = filter_var($body["password"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $verificationCode = rand(1000, 9999);
        $emailBody = "     
        <h1>A te Belépés hitelesítő kódód:</h1>
            <h3>$verificationCode
            </h3> ";

        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            header("Location: /user/login");
            return;
        }
        $hash = $user["password"];

        $isVerified = password_verify($password, $hash);

        if (!$isVerified) {
            header("Location: /user/login");
            return;
        }
        $_SESSION["verificationCode"] = $verificationCode;
        if ($body["remember_me"] === "on") {
            $_SESSION["isRemember"] = true;
        }

        $this->mailer->send($email, $emailBody);

        header("Location: /user/login/verification/" . $user["userId"]);
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
        echo "<pre>";


        header("Location: /private/home");
    }

    public function logout()
    {
        session_start();
        session_destroy();

        $cookieParams = session_get_cookie_params();
        setcookie(session_name(), "", 0, $cookieParams["path"], $cookieParams["domain"], $cookieParams["secure"], isset($cookieParams["httponly"]));
        header('Location: /');
    }

    private function isLoggedIn()
    {
        if (!isset($_COOKIE[session_name()])) return false;
        if (session_id() == '') {
            session_start();
        }
        if (!isset($_SESSION["userId"])) return false;
        return true;
    }


    public function checkUserIsLoggedInOrRedirect()
    {
        if ($this->isLoggedIn()) {
            return;
        };
        header("Location: /");
        exit;
    }
}
