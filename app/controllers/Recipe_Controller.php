<?php
require_once 'app/models/Recipe_Model.php';

class RecipeController
{

    private $loginChecker;
    private $renderer;
    private $userModel;
    private $recipeModel;


    public function __construct()
    {

        $this->loginChecker = new LoginChecker();
        $this->renderer = new Renderer();
        $this->userModel = new UserModel();
        $this->recipeModel = new RecipeModel();
    }

    // User recipes--------------
    public function recipesDashboard()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $user =  $this->userModel->getUserData();
        $recipes =  $this->recipeModel->getRecipesByUser($userId);
        $profileImage = $user["profileImage"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/user/recipes/recipes_dashboard.php", [
                "isSuccess" => $_GET["isSuccess"] ?? null,
                "recipes" => $recipes ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage
        ]);
    }

    public function deleteRecipe($vars)
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $this->recipeModel->delete($vars["id"]);
    }

    public function recipeForm()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $user =  $this->userModel->getUserData();
        $recipes =  $this->recipeModel->getRecipesByUser($userId);
        $profileImage = $user["profileImage"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/user/recipes/Recipe_Form.php", [
                "isSuccess" => $_GET["isSuccess"] ?? null,
                "recipes" => $recipes ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage
        ]);
    }

    public function addNewRecipe() {
        echo "<pre>";
        var_dump(json_decode($_POST["ingredients"], true));
        echo "<br>";
        var_dump($_FILES);
        exit;
    }




























    

    // Public Recipes---------------------------------

    public function  renderRecipes()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $user =  $this->userModel->getUserData();
        $recipes =  $this->recipeModel->getPublicRecipes();
        $profileImage = $user["profileImage"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/public/recipes/recipes.php", [
                "isSuccess" => $_GET["isSuccess"] ?? null,
                "recipes" => $recipes ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage
        ]);
    }
}
