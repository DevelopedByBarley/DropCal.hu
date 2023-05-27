<?php
require_once 'app/models/Recipe_Model.php';

class Queries extends UserModel
{

    private $recipeModel;
    public function __construct()
    {
        parent::__construct();
        $this->recipeModel = new RecipeModel();
    }



    public function getDedicatedRecipesForUser($user)
    {
        $isHaveDiabetes = $user["isHaveDiabetes"];
        $diets = self::getUserDiets($user["userId"]);
        $isGeneralDiet = false;

        foreach ($diets as $diet) {
            if (in_array("Általános", $diet)) {
                $isGeneralDiet = true;
            }
        }


        $query = "SELECT * FROM `recipes` WHERE userRefId = :id";

        if ($isHaveDiabetes == 1) {
            $query .= " AND (glycemic_index <= 50 OR isForDiab = 1)";
        }
        
        switch ($user["goal"]) {
            case 'testsúly_csökkentése':
                $query .= " AND (calorie <= 800)";
                break;
            case 'testsúly_növelése':
                $query .= " AND (calorie >= 900)";
                break;
            default:
                break;
        }


        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $user["userId"]); // Változtasd az $id-t a helyes értékre

        $stmt->execute();
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    

        if (isset($recipes) && !empty($recipes)) { // Ha létezik a recipes és nem üres
            $recipes = self::getRecipesDiets($recipes);
            $recipesWithoutAllergens = self::checkRecipesAllergens($user["allergens"], $recipes); // Megnézzük hogy van-e olyan recept amiben ütköznek a user Allergének a recipes allergénekkel ha nem akkor visszatér 
   
          

            if (empty($recipesWithoutAllergens) || !isset($recipesWithoutAllergens)) {
                return [];
            };

            if (!$isGeneralDiet) { // Ha léteznek a user allergén mentes recptek és nem üresek és a user diet is nem general
                $recipes = $this->compareDiets($diets, $recipes);
            }

            if(isset($recipes) && !empty($recipes)) {
                foreach($recipes as $index => $recipe) {
                   $recipes[$index]["images"] = $this->recipeModel->getRecipeImagesById($recipe["recipeId"]);
                }
            }


            return $recipes; // Return!!!!!!!!!!!!!!!!
        }
    }



    private function getRecipesDiets($recipes)
    {
        foreach ($recipes as $index => $recipe) {
            $stmt = $this->pdo->prepare("SELECT * FROM `recipe_diets` WHERE recipeRefId = :id");
            $stmt->bindParam(":id", $recipe["recipeId"]);
            $stmt->execute();

            $diets = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $recipes[$index]["diets"] = $diets;
        }

        return $recipes;
    }


    private function getUserDiets($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `user_diets` WHERE userRefId = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $diets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $diets;
    }

    private function compareDiets($diets, $recipes)
    {
        $ret = [];
        foreach ($recipes as $recipe) {
            foreach ($recipe["diets"] as $recipeDiet) {
                foreach ($diets as $diet) {
    
                    if (($diet["diet"] === $recipeDiet["r_diet"]) || ($recipeDiet["r_diet"] === "general")) {
                        if(!in_array($recipe, $ret)) {
                            $ret[] = $recipe;
                        }
                    }
                }
            }
        }

        return $ret;
    }

    private function checkRecipesAllergens($userAllergensData, $recipes)
    {
        $recipesWithoutAllergens = [];
        $userAllergens = array_column($userAllergensData, 'allergenNumber');
        $addedRecipes = []; // Segéd tömb a már hozzáadott receptek tárolására

        foreach ($recipes as $recipe) {
            $recipe["allergens"] = json_decode($recipe["allergens"], true);

            if (!empty($recipe["allergens"])) {
                foreach ($userAllergens as $userAllergen) {
                    if (!in_array((int)$userAllergen, $recipe["allergens"])) {
                        // Ellenőrizd, hogy az adott recept már szerepel-e a segéd tömbben
                        if (!in_array($recipe["recipeId"], $addedRecipes)) {
                            $recipesWithoutAllergens[] = $recipe;
                            $addedRecipes[] = $recipe["recipeId"];
                        }
                    }
                }
            } else {
                // Ellenőrizd, hogy az adott recept már szerepel-e a segéd tömbben
                if (!in_array($recipe["recipeId"], $addedRecipes)) {
                    $recipesWithoutAllergens[] = $recipe;
                    $addedRecipes[] = $recipe["recipeId"];
                }
            }
        }

        return $recipesWithoutAllergens;
    }
}
