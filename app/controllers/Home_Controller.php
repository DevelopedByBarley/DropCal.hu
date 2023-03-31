<?php

class HomeController
{

    private $renderer;
    public function __construct()
    {
        $this->renderer = new Renderer();
    }

    public function getHomePage()
    {
        session_start();
        $userId = $_SESSION["userId"] ?? null;

        if (!$userId) {
            echo $this->renderer->render("Layout.php", [
                "content" => $this->renderer->render("/pages/public/Home.php", []),
                "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            ]);
        } else {

            


            echo $this->renderer->render("Layout.php", [
                "content" => $this->renderer->render("/pages/public/Home.php", []),
                "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
                "userId" => $userId
            ]);
        }
    }
}
