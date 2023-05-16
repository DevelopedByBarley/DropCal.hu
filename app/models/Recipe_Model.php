<?php
require_once 'app/models/User_Model.php';

class RecipeModel extends UserModel
{
    public function __construct()
    {
        parent::__construct();
    }

    // Public diarie-s lekérése
    public function getRecipesByUser($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM  `recipes` WHERE userRefId = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $recipes;
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM  `recipes` WHERE recipeId = :id");
        $stmt->bindParam(":id", $id);
        $isSuccess = $stmt->execute();

        if ($isSuccess) {
            header("Location: /user/recipes-dashboard");
        }
    }




    public function getPublicRecipes()
    {
        $isPublic = 1;

        $stmt = $this->pdo->prepare("SELECT * FROM  `recipes` WHERE `isPublic` = :isPublic");
        $stmt->bindParam(":isPublic", $isPublic);
        $stmt->execute();

        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $recipes;
    }


    public function addRecipe($body, $files)
    {
        $recipeIngredients = json_decode($body["ingredients"], true);

    }
}
