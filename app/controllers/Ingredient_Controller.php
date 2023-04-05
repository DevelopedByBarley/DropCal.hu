<?php
require_once 'app/controllers/Diary_Controller.php';
require_once "app/models/Ingredient_Model.php";
class IngredientController extends DiaryController
{
    private $ingredientModel;
    private $loginChecker;

    public function __construct()
    {
        parent::__construct();
        $this->ingredientModel = new IngredientModel();
        $this->loginChecker = new LoginChecker();
    }

    public function getIngredientsByUser()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $user =  $this->userModel->getUserData();
        $profileImage = $user["profileImage"];
        $ingredients = $this->ingredientModel->getIngredients($_POST, $userId);
        $ingredientCategories = ["Fagylalt", "Jégkrém", "Gomba", "Gabona", "Gyümölcs", "Hal", "Húskészítmény", "Ital", "Készétel", "Köret", "Leves", "Olaj", "Pékáru", "Édesség", "Sütemény", "Rágcsa", "Tészta", "Tejtermék"];
       

        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/ingredients/Ingredient_List.php", [
                "ingredients" => $ingredients,
                "ingredientCategories" => $ingredientCategories,
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage
        ]);
    }

    public function addIngredient() {
        echo "<pre>";
        var_dump($_POST);
        var_dump(json_decode($_POST["allergens"], true));
    }
}
