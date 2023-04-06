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
        $units = ["100g", "100ml", "bögre", "csésze", "csomag", "darab", "doboz", "egész", "üveg", "tubus", "pohár", "szelet", "gerezd"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/user/ingredients/Ingredient_List.php", [
                "ingredients" => $ingredients,
                "ingredientCategories" => $ingredientCategories,
                "units" => $units,
                "isSuccess" => $_GET["isSuccess"] ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage
        ]);
    }

    public function addIngredient()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $_POST = json_decode(file_get_contents('php://input'), true);
        $isSuccess  = $this->ingredientModel->addIngredient($_POST);

        if(!$isSuccess) {
            echo json_encode([
                "state" => false,
            ]);
            return;
        }

        echo json_encode([
            "state" => true,
        ]);
    }
}
