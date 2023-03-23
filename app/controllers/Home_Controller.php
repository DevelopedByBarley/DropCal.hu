<?php
require './app/models/Home_Model.php';

class HomeController
{
    private $homeModel;
    private $mailer;
    private $renderer;
    public function __construct()
    {
        $this->homeModel = new HomeModel();
        $this->mailer = new Mailer();
        $this->renderer = new Renderer();
    }

    public function getHomePage()
    {
        

        echo $this->renderer->render("Layout.php",[
            "content" => $this->renderer->render("/pages/Home.php", [])
        ]);
    }

}
