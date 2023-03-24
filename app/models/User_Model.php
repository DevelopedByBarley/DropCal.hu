<?php
class UserModel
{

    protected $pdo;
    protected $calculators;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnect();
        $this->calculators = new Calculators();
    }


    public function register($files) {
        session_start();
        $userName = $_SESSION["registrationData"]["userName"] ?? '';
        $email = $_SESSION["registrationData"]["email"] ?? '';
        $password = $_SESSION["registrationData"]["password"] ?? '';
        $yearOfBirth = (int)$_SESSION["registrationData"]["yearOfBirth"] ?? '';
        $sex = $_SESSION["registrationData"]["sex"] ?? '';
        $currentWeight = (int)$_SESSION["registrationData"]["currentWeight"] ?? '';
        $height = (int)$_SESSION["registrationData"]["height"] ?? '';
        $activity = $_SESSION["registrationData"]["activity"] ?? '';
        $allergens = $_SESSION["registrationData"]["allergens"] ?? '';
        $isHaveDiabetes = $_SESSION["registrationData"]["isHaveDiabetes"] ?? '';
        $goal = $_SESSION["registrationData"]["goal"] ?? '';

        $BMI = $this->calculators->calculateBMI($currentWeight, $height);
        $resultOfBMI = $this->calculators->resultOfBMI($BMI);
        $calculateBMR = $this->calculators->calculateBMR($sex, $currentWeight, $height, $yearOfBirth, $activity);
        $BMR = $calculateBMR["BMR"];
        $activityBMR = $calculateBMR["activityBMR"];
        $finalBMR = $this->calculators->resultOfGoal($activityBMR, $goal);

        echo "<h3 class=''>BMI : $BMI</h3>";
        echo "<h3>BMI STÁTUSZ : ". $resultOfBMI["state"] . "</h3>";
        echo "<h3>EGÉSZSÉGÜGYI KOCKÁZAT  : ". $resultOfBMI["riskOfHealth"] . "</h3>";
        echo "<h3>ALAPJÁRAT KALÓRIA : $BMR</h3>";
        echo "<h3>KALÓRISZÜKSÉGLET NAPONTA : $finalBMR</h3>";
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

        if ($currentPageId < $this->minStep) {
            header("Location: /user/registration/0");
        };
        session_start();
        return $this->registrationTabs[$currentPageId];
    }
    public function nextStep($vars, $body)
    {


        $currentPageId = $vars["id"];

        if ($currentPageId > $this->maxStep) {
            header("Location: /user/registration/" . $this->maxStep);
        }

        $this->setRegSession($body);

        return $this->registrationTabs[$currentPageId];
    }

    public function setRegSession($body): void
    {
        session_start();
        $data = isset($_SESSION["registrationData"]) ? $_SESSION["registrationData"] : [];
        if (isset($body) && !empty($body)) {
            foreach ($body as $key => $posts) {
                $data[$key] = $posts;
                $_SESSION["registrationData"] = $data;
            }
        }
    }
}
