<?php

class RecipeController
{

    private $recipeModel;
    private $loginChecker;
    private $renderer;
    private $userModel;
    public function __construct()
    {

        $this->loginChecker = new LoginChecker();
        $this->renderer = new Renderer();
        $this->userModel = new UserModel();
    }


   public function recipes() {
        var_dump("Hello Recipes");
   }
}
