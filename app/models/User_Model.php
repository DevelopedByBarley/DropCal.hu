<?php

class UserModel
{

    protected $pdo;
    protected $calculators;
    protected $cookie;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnect();
        $this->calculators = new Calculators();
        $this->cookie = new Coookie();
    }


    public function register($files)
    {

        $userData = json_decode($_COOKIE["registrationData"], true);

        $userName = $userData["userName"] ?? '';
        $email = $userData["email"] ?? '';
        $password = $userData["password"] ?? '';
        $yearOfBirth = (int)$userData["yearOfBirth"] ?? '';
        $sex = $userData["sex"] ?? '';
        $currentWeight = (int)$userData["currentWeight"] ?? '';
        $height = (int)$userData["height"] ?? '';
        $activity = $userData["activity"] ?? '';
        $allergens = $userData["allergens"] ?? '';
        $isHaveDiabetes = $userData["isHaveDiabetes"] ?? '';
        $goal = $userData["goal"] ?? '';


        $BMI = $this->calculators->calculateBMI($currentWeight, $height);
        $resultOfBMI = $this->calculators->resultOfBMI($BMI);
        $calculateBMR = $this->calculators->calculateBMR($sex, $currentWeight, $height, $yearOfBirth, $activity);
        $BMR = $calculateBMR["BMR"];
        $activityBMR = $calculateBMR["activityBMR"];
        $finalBMR = $this->calculators->resultOfGoal($activityBMR, $goal);
        $macros = $this->calculators->getMacro($finalBMR);




        echo "<h3 class=''>BMI : $BMI</h3>";
        echo "<h3>BMI STÁTUSZ : " . $resultOfBMI["state"] . "</h3>";
        echo "<h3>EGÉSZSÉGÜGYI KOCKÁZAT  : " . $resultOfBMI["riskOfHealth"] . "</h3>";
        echo "<h3>ALAPJÁRAT KALÓRIA : $BMR kcal</h3>";
        echo "<h3>ALAPJÁRAT Aktivitással : $activityBMR kcal</h3>";
        echo "<h3>Végleges KALÓRISZÜKSÉGLET NAPONTA : $finalBMR kcal</h3>";
        echo "<br>";
        echo "<h3>FEHÉRJE SZÜKSÉGLET NAPONTA : " . $macros["protein"] . "g</h3>";
        echo "<h3>SZÉNHIDRÁT SZÜKSÉGLET NAPONTA : " . $macros["carbohydrate"] . "g</h3>";
        echo "<h3>ZSIR SZÜKSÉGLET NAPONTA : " . $macros["fat"] . "g</h3>";

        $this->cookie->setCookie('registrationData', '', time() - 3600, '/');
        $this->cookie->setCookie('currentStepId', '', time() - 3600, '/');

        exit;
        header("Location: /");
    }
}






class StepModel extends UserModel
{
    private  $registrationTabs = [
        "greetings.php",
        "sub_data.php",
        "personal_data.php",
        "allergens.php",
        "check_diab.php",
        "profile_image.php",
    ];

    private  $minStep = 0;
    private  $maxStep;

    public function __construct()
    {
        parent::__construct();
        $this->maxStep = count($this->registrationTabs) - 1;
    }


    public function prevStep($vars)
    {
        $currentPageId = $vars["id"];

        if (($currentPageId < $this->minStep) || ($currentPageId > 2 && !isset($_COOKIE["registrationData"]))) {
            $this->cookie->setCookie('currentStepId', '', time() - 3600, '/');
            header("Location: /user/registration/0");
            exit;
        };
        $expires = time() + (30 * 24 * 60 * 60);
        $this->cookie->setCookie('currentStepId', $currentPageId, $expires, '/');
        return $this->registrationTabs[$currentPageId];
    }
    public function nextStep($vars, $body)
    {


        $currentPageId = $vars["id"];

        if (($currentPageId > $this->maxStep) || ($currentPageId > 2 && !isset($_COOKIE["registrationData"]))) {
            $this->cookie->setCookie('currentStepId', '', time() - 3600, '/');
            header("Location: /user/registration/" . $this->maxStep);
            exit;
        }

        $this->setRegCookies($body);
        $expires = time() + (30 * 24 * 60 * 60);
        $this->cookie->setCookie('currentStepId', $currentPageId, $expires, '/');
        return $this->registrationTabs[$currentPageId];
    }

    public function setRegCookies($body): void
    {
        $expires = time() + (30 * 24 * 60 * 60);
        $data = isset($_COOKIE["registrationData"]) ? json_decode($_COOKIE["registrationData"], true) : [];
        if (isset($body) && !empty($body)) {
            foreach ($body as $key => $posts) {
                $data[$key] = $posts;
            }
            $this->cookie->setCookie('registrationData', json_encode($data), $expires, '/');
        }
    }
}
