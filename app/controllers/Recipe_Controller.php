<?php
require_once 'app/models/Recipe_Model.php';

class RecipeController
{

    private $loginChecker;
    private $renderer;
    private $userModel;
    private $recipeModel;
    private $toast;

    public function __construct()
    {

        $this->loginChecker = new LoginChecker();
        $this->renderer = new Renderer();
        $this->userModel = new UserModel();
        $this->recipeModel = new RecipeModel();
        $this->toast = new Toast(); 
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
        $this->toast->getToastMessageAndShow();


        if (isset($_GET["clearLocalStorage"]) && $_GET["clearLocalStorage"] === "true") {
            echo "<script>localStorage.clear();</script>";
        }
    }
public function deleteRecipe($vars)
{
    $this->loginChecker->checkUserIsLoggedInOrRedirect();
    $isSuccess = $this->recipeModel->delete($vars["id"]);

    if ($isSuccess) {
        $this->toast->setToastMessage("Recept sikeresen törölve!");
        header("Location: /user/recipes-dashboard");

        exit; // Kilépés a script futtatásából
    }
}

    public function recipeForm()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $user =  $this->userModel->getUserData();
        $profileImage = $user["profileImage"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/user/recipes/Recipe_Form.php", [
                "isSuccess" => $_GET["isSuccess"] ?? null,
                "recipes" => $recipes ?? null,
                "meals" => MEALS,
                "diets" => DIETS,
                "ingredientCategories" => INGREDIENT_CATEGORIES,
                "units" => UNITS,
                "common_units" => COMMON_UNITS
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage
        ]);
    }

    public function addNewRecipe()
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $recipeIngredients = json_decode($_POST["ingredients"], true);

        if (empty($recipeIngredients)) {
            header("Location: /user/recipe/new");
            exit;
        }

        $this->recipeModel->addRecipe($_POST, $_FILES, $userId);
        $this->toast->setToastMessage("Recept sikeresen hozzáadva!");

        header("Location: /user/recipes-dashboard?clearLocalStorage=true");
        exit;
    }

    public function updateRecipeForm($vars)
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $user =  $this->userModel->getUserData();
        $recipeForUpdate = $this->recipeModel->getRecipeById($vars["id"]);
        $profileImage = $user["profileImage"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/user/recipes/Recipe_Form.php", [
                "isSuccess" => $_GET["isSuccess"] ?? null,
                "recipeForUpdate" => $recipeForUpdate ?? null,
                "meals" => MEALS,
                "diets" => DIETS,
                "ingredientCategories" => INGREDIENT_CATEGORIES,
                "units" => UNITS,
                "common_units" => COMMON_UNITS
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage
        ]);
    }


    public function updateRecipe($vars)
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $this->recipeModel->update($_POST, $_FILES, $vars["id"], $userId);
        $this->toast->setToastMessage("Recept sikeresen frissitve   !");

        header("Location: /user/recipes-dashboard?clearLocalStorage=true");
    }


    public function recipeSingle($vars)
    {
        $this->loginChecker->checkUserIsLoggedInOrRedirect();
        $userId = $_SESSION["userId"] ?? null;
        $user =  $this->userModel->getUserData();
        $recipe = $this->recipeModel->getRecipeById($vars["id"]);
        $profileImage = $user["profileImage"];
        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/private/user/recipes/Recipe_Single.php", [
                "isSuccess" => $_GET["isSuccess"] ?? null,
                "recipe" => $recipe ?? null
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,
            "userId" => $userId,
            "profileImage" => $profileImage
        ]);
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
