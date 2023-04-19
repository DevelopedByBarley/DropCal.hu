<?php
require 'app/controllers/User_Controller.php';

class Step extends UserController
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
    private $cookie;

    public function __construct()
    {
        parent::__construct();
        $this->maxStep = count($this->registrationTabs) - 1;
        $this->cookie = new Cookie();
    }

    public function prevStep($vars)
    {
        $currentStepPage = $this->setPrevStep($vars);
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/public/user/subscription/Registration/$currentStepPage", [
                "registrationData" => $this->registrationData
            ]),
            "currentStepId" => $_COOKIE["currentStepId"] ?? 0
        ]);
    }

    public function nextStep($vars)
    {
        $currentStepPage = $this->setNextStep($vars, $_POST);
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/public/user/subscription/Registration/$currentStepPage", [
                "registrationData" => $this->registrationData,
                "isVerificationFail" => $_GET["isVerificationFail"] ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0
        ]);
    }


    private function setPrevStep($vars)
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
    private function setNextStep($vars, $body)
    {

        session_start();
        $currentPageId = $vars["id"];

        if (($currentPageId > $this->maxStep) || ($currentPageId > 2 && !isset($_COOKIE["registrationData"]))) {
            $this->cookie->setCookie('currentStepId', '', time() - 3600, '/');
            header("Location: /user/registration/" . $this->maxStep);
            exit;
        }

        if ((int)$currentPageId >= 2 && !isset($_SESSION["isEmailVerified"])) {
            $expires = time() + (30 * 24 * 60 * 60);
            $this->cookie->setCookie('registrationData', json_encode($_POST), $expires, '/');
            header("Location: /user/registration/1");
            exit;
        }

        $this->setRegCookies($body);
        $expires = time() + (30 * 24 * 60 * 60);
        $this->cookie->setCookie('currentStepId', $currentPageId, $expires, '/');
        return $this->registrationTabs[$currentPageId];
    }

    private function setRegCookies($body): void
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
