<?php
require 'app/models/User_Model.php';

class StepModel extends UserModel
{
    private  $registrationTabs = [
        "greetings.php",
        "sub_data.php",
        "personal_data.php",
        "allergens.php",
        "diet.php",
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

        session_start();
        $currentPageId = $vars["id"];
        
        if (($currentPageId > $this->maxStep) || ($currentPageId > 2 && !isset($_COOKIE["registrationData"]))) {
            $this->cookie->setCookie('currentStepId', '', time() - 3600, '/');
            header("Location: /user/registration/" . $this->maxStep);
            exit;
        }
        
        if((int)$currentPageId >= 2 && !isset($_SESSION["isEmailVerified"])) {
            header("Location: /user/registration/1?isVerificationFail=1");
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
