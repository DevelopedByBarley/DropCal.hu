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


    public function addRecipe($body, $files, $userId)
    {

        $recipeIngredients = json_decode($body["ingredients"], true);
        $macros = json_decode($body["macros"], true);
        $recipe_name = $body["name"];
        $calorie = $macros["sumOfCalorie"];
        $protein = $macros["sumOfProtein"];
        $carb = $macros["sumOfCarb"];
        $fat = $macros["sumOfFat"];
        $GI = $body["glycemic_index_summary"];
        $allergens = $body["allergens"];

        $isPublic = $body["isPublic"] === 'on' ? 1 : 0;

        $stmt = $this->pdo->prepare(
            "INSERT INTO 
        `recipes` 
        (`recipeId`, 
        `recipe_name`, 
        `calorie`, 
        `protein`, 
        `carb`, 
        `fat`, 
        `glycemic_index`, 
        `allergens`, 
        `isPublic`, 
        `userRefId`) 
        VALUES 
        (NULL, 
        :recipe_name, 
        :calorie, 
        :protein, 
        :carb, 
        :fat, 
        :glycemic_index, 
        :allergens,
        :isPublic, 
        :userRefId);"
        );


        $stmt->bindParam(':recipe_name', $recipe_name, PDO::PARAM_STR);
        $stmt->bindParam(':calorie', $calorie, PDO::PARAM_INT);
        $stmt->bindParam(':protein', $protein, PDO::PARAM_INT);
        $stmt->bindParam(':carb', $carb, PDO::PARAM_INT);
        $stmt->bindParam(':fat', $fat, PDO::PARAM_INT);
        $stmt->bindParam(':glycemic_index', $GI, PDO::PARAM_INT);
        $stmt->bindParam(':allergens', $allergens, PDO::PARAM_STR);
        $stmt->bindParam(':isPublic', $isPublic, PDO::PARAM_INT);
        $stmt->bindParam(':userRefId', $userId, PDO::PARAM_INT);

        $stmt->execute();

        $lastInsertedId = $this->pdo->lastInsertId();

        if ($lastInsertedId) {

            foreach ($recipeIngredients as $ingredient) {
                $allergens = json_encode($ingredient["allergens"]);
                $stmt = $this->pdo->prepare("INSERT INTO `recipe_ingredients` 
                    (
                        `recipeIngredientId`, 
                        `id`, 
                        `ingredientName`, 
                        `ingredientCategorie`,
                        `unit`, 
                        `unit_quantity`, 
                        `calorie`,
                        `common_unit`, 
                        `common_unit_quantity`, 
                        `common_unit_ex`, 
                        `protein`, 
                        `carb`, 
                        `fat`, 
                        `glycemicIndex`, 
                        `allergens`, 
                        `recipeRefId`
                    ) 
                    VALUES 
                    (
                        NULL,
                        :id,
                        :ingredientName, 
                        :ingredientCategorie, 
                        :unit, 
                        :unit_quantity, 
                        :calorie, 
                        :common_unit, 
                        :common_unit_quantity, 
                        :common_unit_ex, 
                        :protein, 
                        :carb, 
                        :fat, 
                        :glycemicIndex, 
                        :allergens, 
                        :recipeRefId
                    );");

                $stmt->bindParam(':id', $ingredient['id']);
                $stmt->bindParam(':ingredientName', $ingredient['ingredientName']);
                $stmt->bindParam(':ingredientCategorie', $ingredient['ingredientCategorie']);
                $stmt->bindParam(':unit', $ingredient['unit']);
                $stmt->bindParam(':unit_quantity', $ingredient['unit_quantity']);
                $stmt->bindParam(':calorie', $ingredient['calorie']);
                $stmt->bindParam(':common_unit', $ingredient['commonUnit']);
                $stmt->bindParam(':common_unit_quantity', $ingredient['common_unit_quantity']);
                $stmt->bindParam(':common_unit_ex', $ingredient['common_unit_ex']);
                $stmt->bindParam(':protein', $ingredient['protein']);
                $stmt->bindParam(':carb', $ingredient['carb']);
                $stmt->bindParam(':fat', $ingredient['fat']);
                $stmt->bindParam(':glycemicIndex', $ingredient['glycemicIndex']);
                $stmt->bindParam(':allergens', $allergens);
                $stmt->bindParam(':recipeRefId', $lastInsertedId);

                $stmt->execute();    

            }
        }
    }
}
