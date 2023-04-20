<?php
require_once 'app/controllers/Diary_Controller.php';
require_once "app/models/Ingredient_Model.php";
class IngredientController extends DiaryController
{
    private $ingredientModel;
    private $loginChecker;
    private $userModel;
    private $renderer;
    public function __construct()
    {
        parent::__construct();
        $this->ingredientModel = new IngredientModel();
        $this->loginChecker = new LoginChecker();
        $this->userModel = new UserModel();
        $this->renderer = new Renderer();
    }

    public function getIngredientsByUser()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $user =  $this->userModel->getUserData();
        $profileImage = $user["profileImage"];
        $ingredients = $this->ingredientModel->getIngredients($_POST, $userId);
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/user/ingredients/Ingredient_List.php", [
                "ingredients" => $ingredients,
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

        if (!$isSuccess) {
            echo json_encode([
                "state" => false,
            ]);
            return;
        }

        echo json_encode([
            "state" => true,
        ]);
    }

    public function getIngredientForm()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $ingredientCategories = ["Fagylalt", "Jégkrém", "Gomba", "Gabona", "Gyümölcs", "Hal", "Húskészítmény", "Ital", "Készétel", "Köret", "Leves", "Olaj", "Pékáru", "Édesség", "Sütemény", "Rágcsa", "Tészta", "Tejtermék"];
        $units = ["100g", "100ml"];
        $common_units = ["Darab", "Bögre"];

        $userId = $_SESSION["userId"] ?? null;
        $user =  $this->userModel->getUserData();
        $profileImage = $user["profileImage"];

        $ingredientId = $_GET["id"] ?? null;
        if($ingredientId) {
           $ingredient =  $this->ingredientModel->getIngredientById($ingredientId);
        }

        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/user/ingredients/Ingredient_Form.php", [
                "ingredientCategories" => $ingredientCategories,
                "units" => $units,
                "common_units" => $common_units,
                "ingredientId" => $ingredientId,
                "ingredient" => $ingredient ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage
        ]);
    }


    public function deleteIngredient($vars) {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $id = $vars["id"];

        $this->ingredientModel->delete($id);

    }

}
