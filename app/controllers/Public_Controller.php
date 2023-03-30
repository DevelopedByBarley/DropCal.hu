<?php
require './app/models/Public_Model.php';

class PublicController
{
    private $homeModel;
    private $mailer;
    private $renderer;
    public function __construct()
    {
        $this->homeModel = new PublicModel();
        $this->mailer = new Mailer();
        $this->renderer = new Renderer();
    }

    public function getHomePage()
    {

        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/public/Home.php", []),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0
        ]);
    }
}
