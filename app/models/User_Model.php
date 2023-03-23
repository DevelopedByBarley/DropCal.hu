<?php
class UserModel
{

    protected $pdo;


    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnect();
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
