<?php
require_once 'app/models/User_Model.php';

class UserController
{
    protected $renderer;
    protected $userModel;
    protected $stepModel;
    public function __construct()
    {

        $this->renderer = new Renderer();
        $this->userModel = new UserModel();
        $this->stepModel = new StepModel();
    }

    public function registrationStart()
    {
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/subscription/Registration/greetings.php", [])
        ]);
    }

    public function registration()
    {
        $this->userModel->register($_FILES);
    }
}

class StepsController extends UserController
{
    public function __construct()
    {
        parent::__construct();
    }




    public function prevStep($vars)
    {
        $currentStepPage = $this->stepModel->prevStep($vars);
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/subscription/Registration/$currentStepPage", [])
        ]);
    }

    public function nextStep($vars)
    {
        $currentStepPage = $this->stepModel->nextStep($vars, $_POST);
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/subscription/Registration/$currentStepPage", [])
        ]);
    }
}
