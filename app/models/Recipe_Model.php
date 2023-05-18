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

        $recipeData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $recipes = $this->getImagesByRecipes($recipeData);

        return $recipes;
    }


    private function getImagesByRecipes($recipes)
    {
        foreach ($recipes as &$recipe) {
            $images = $this->getRecipeImagesById($recipe["recipeId"]);
            $recipe["images"] = $images;
        }

        return $recipes;
    }
    public function delete($id)
    {

        $images = $this->getRecipeImagesById($id);
        foreach ($images as $image) {
            unlink("./public/assets/recipe_images/" . $image["r_imageName"]);
        }

        $stmt = $this->pdo->prepare("DELETE FROM  `recipes` WHERE recipeId = :id");
        $stmt->bindParam(":id", $id);
        $isSuccess = $stmt->execute();

        if ($isSuccess) {

            header("Location: /user/recipes-dashboard");
        }
    }



    public function getRecipeImagesById($id)
    {
        $stmt = $this->pdo->prepare("SELECT `r_imageName` FROM  `recipe_images` WHERE recipeRefId = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $images;
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

    public function update($body, $files, $recipeId, $userId)
    {
        $recipeIngredients = json_decode($body["ingredients"], true);
        $steps = $body["steps"];
        $macros = json_decode($body["macros"], true);
        $recipe_name = $body["name"];
        $meal = $body["meal"];
        $diet = $body["diet"];
        $calorie = $macros["sumOfCalorie"];
        $protein = $macros["sumOfProtein"];
        $carb = $macros["sumOfCarb"];
        $fat = $macros["sumOfFat"];
        $GI = $body["glycemic_index_summary"];
        $allergens = $body["allergens"];

        $isPublic = isset($body["isPublic"]) && $body["isPublic"] === 'on' ? 1 : 0;

        $stmt = $this->pdo->prepare(
            "UPDATE `recipes` SET 
            `recipe_name` = :recipe_name,
            `meal` = :meal,
            `diet` = :diet,
            `calorie` = :calorie,
            `protein` = :protein,
            `carb` = :carb,
            `fat` = :fat,
            `glycemic_index` = :glycemic_index,
            `allergens` = :allergens,
            `isPublic` = :isPublic,
            `userRefId` = :userRefId 
            WHERE `recipeId` = :recipeId;"
        );


        $stmt->bindParam(':recipe_name', $recipe_name, PDO::PARAM_STR);
        $stmt->bindParam(':meal', $meal, PDO::PARAM_STR);
        $stmt->bindParam(':diet', $diet, PDO::PARAM_STR);
        $stmt->bindParam(':calorie', $calorie, PDO::PARAM_INT);
        $stmt->bindParam(':protein', $protein, PDO::PARAM_INT);
        $stmt->bindParam(':carb', $carb, PDO::PARAM_INT);
        $stmt->bindParam(':fat', $fat, PDO::PARAM_INT);
        $stmt->bindParam(':glycemic_index', $GI, PDO::PARAM_INT);
        $stmt->bindParam(':allergens', $allergens, PDO::PARAM_STR);
        $stmt->bindParam(':isPublic', $isPublic, PDO::PARAM_INT);
        $stmt->bindParam(':userRefId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);

        $isSuccess = $stmt->execute();



        if ($isSuccess) {
            $this->updateRecipeSteps($steps, $recipeId);
            $this->updateRecipeIngredients($recipeIngredients, $recipeId);

            if($files["files"]["name"][0] !== "") $this->updateRecipeImages($files, $recipeId);
        }
    }

    private function updateRecipeImages($files, $recipeRefId)
    {
        $images = $this->getRecipeImagesById($recipeRefId);
        foreach ($images as $image) {
            unlink("./public/assets/recipe_images/" . $image["r_imageName"]);
        }


        $stmt = $this->pdo->prepare("DELETE FROM `recipe_images` WHERE `recipeRefId` = :recipeRefId");
        $stmt->bindParam(":recipeRefId", $recipeRefId);
        $isSuccess = $stmt->execute();

        if ($isSuccess) {
            $recipeImages = $this->fileSaver->saver($files["files"], "/recipe_images", null);
            $this->insertRecipeImages($recipeImages,  $recipeRefId);
        }
    }

    private function updateRecipeSteps($steps, $recipeRefId)
    {


        $stmt = $this->pdo->prepare("DELETE FROM `recipe_steps` WHERE `recipeRefId` = :recipeRefId");
        $stmt->bindParam(":recipeRefId", $recipeRefId);
        $stmt->execute();

        foreach ($steps as $step) {
            $stmt = $this->pdo->prepare("INSERT INTO `recipe_steps` (`id`, `content`, `recipeRefId`) VALUES (NULL, :content, :recipeRefId);");
            $stmt->bindParam(":content", $step);
            $stmt->bindParam(":recipeRefId", $recipeRefId);
            $stmt->execute();
        }
    }

    private function updateRecipeIngredients($recipeIngredients, $recipeRefId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `recipe_ingredients` WHERE `recipeRefId` = :recipeRefId");
        $stmt->bindParam(":recipeRefId", $recipeRefId);
        $stmt->execute();
        foreach ($recipeIngredients as $ingredient) {
            $stmt = $this->pdo->prepare("INSERT INTO `recipe_ingredients` 
            (
                recipeIngredientId,
                id,
                ingredientName,
                ingredientCategorie,
                unit,
                unit_quantity,
                calorie,
                common_unit,
                common_unit_quantity,
                common_unit_ex,
                protein,
                carb,
                fat,
                glycemicIndex,
                ingredientUnits,
                currentCalorie,
                currentProtein,
                currentCarb,
                currentFat,
                allergens,
                recipeRefId
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
                :ingredientUnits,
                :currentCalorie,
                :currentProtein,
                :currentCarb,
                :currentFat, 
                :allergens, 
                :recipeRefId
            );");

            $stmt->bindParam(':id', $ingredient['id']);
            $stmt->bindParam(':ingredientName', $ingredient['ingredientName']);
            $stmt->bindParam(':ingredientCategorie', $ingredient['ingredientCategorie']);
            $stmt->bindParam(':unit', $ingredient['unit']);
            $stmt->bindParam(':unit_quantity', $ingredient['unit_quantity']);
            $stmt->bindParam(':calorie', $ingredient['calorie']);
            $stmt->bindParam(':common_unit', $ingredient['common_unit']);
            $stmt->bindParam(':common_unit_quantity', $ingredient['common_unit_quantity']);
            $stmt->bindParam(':common_unit_ex', $ingredient['common_unit_ex']);
            $stmt->bindParam(':protein', $ingredient['protein']);
            $stmt->bindParam(':carb', $ingredient['carb']);
            $stmt->bindParam(':fat', $ingredient['fat']);
            $stmt->bindParam(':glycemicIndex', $ingredient['glycemicIndex']);
            $stmt->bindParam(':currentCalorie', $ingredient['currentCalorie']);
            $stmt->bindParam(':currentProtein', $ingredient['currentProtein']);
            $stmt->bindParam(':currentCarb', $ingredient['currentCarb']);
            $stmt->bindParam(':currentFat', $ingredient['currentFat']);

            $allergens = json_encode($ingredient["allergens"]);
            $stmt->bindParam(':allergens', $allergens);

            $ingredientUnits = json_encode($ingredient["ingredientUnits"]);
            $stmt->bindParam(':ingredientUnits', $ingredientUnits);

            $stmt->bindParam(':recipeRefId', $recipeRefId);
            $stmt->execute();
        }

    }

    public function addRecipe($body, $files, $userId)
    {

        $recipeIngredients = json_decode($body["ingredients"], true);
        $steps = $body["steps"];
        $macros = json_decode($body["macros"], true);
        $recipe_name = $body["name"];
        $meal = $body["meal"];
        $diet = $body["diet"];
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
        VALUES 
        (NULL, 
        :recipe_name, 
        :meal, 
        :diet, 
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
        $stmt->bindParam(':meal', $meal, PDO::PARAM_STR);
        $stmt->bindParam(':diet', $diet, PDO::PARAM_STR);
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
            $recipeImages = $this->fileSaver->saver($files["files"], "/recipe_images", null);
            $this->insertRecipeImages($recipeImages,  $lastInsertedId);
            $this->insertRecipeIngredients($recipeIngredients, $lastInsertedId);
            $this->insertRecipeSteps($steps, $lastInsertedId);
        }
    }

    private function insertRecipeImages($recipeImages,  $lastInsertedId)
    {
        foreach ($recipeImages as $image) {

            $stmt = $this->pdo->prepare("INSERT INTO `recipe_images` (`r_imageId`, `r_imageName`, `recipeRefId`) VALUES (NULL, :name, :recipeRefId);");
            $stmt->bindParam(":name", $image);
            $stmt->bindParam(":recipeRefId", $lastInsertedId);

            $stmt->execute();
        }
    }

    private function insertRecipeSteps($steps, $lastInsertedId)
    {
        foreach ($steps as $step) {
            $stmt = $this->pdo->prepare("INSERT INTO `recipe_steps` (`id`, `content`, `recipeRefId`) VALUES (NULL, :content, :recipeRefId);");
            $stmt->bindParam(":content", $step);
            $stmt->bindParam(":recipeRefId", $lastInsertedId);
            $stmt->execute();
        }
    }


    private function insertRecipeIngredients($recipeIngredients, $lastInsertedId)
    {
        foreach ($recipeIngredients as $ingredient) {

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
                `ingredientUnits`,
                `currentCalorie`,
                `currentProtein`,
                `currentCarb`,
                `currentFat`,
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
                :ingredientUnits,
                :currentCalorie,
                :currentProtein,
                :currentCarb,
                :currentFat, 
                :allergens, 
                :recipeRefId
            );");

            $stmt->bindParam(':id', $ingredient['id']);
            $stmt->bindParam(':ingredientName', $ingredient['ingredientName']);
            $stmt->bindParam(':ingredientCategorie', $ingredient['ingredientCategorie']);
            $stmt->bindParam(':unit', $ingredient['unit']);
            $stmt->bindParam(':unit_quantity', $ingredient['unit_quantity']);
            $stmt->bindParam(':calorie', $ingredient['calorie']);
            $stmt->bindParam(':common_unit', $ingredient['common_unit']);
            $stmt->bindParam(':common_unit_quantity', $ingredient['common_unit_quantity']);
            $stmt->bindParam(':common_unit_ex', $ingredient['common_unit_ex']);
            $stmt->bindParam(':protein', $ingredient['protein']);
            $stmt->bindParam(':carb', $ingredient['carb']);
            $stmt->bindParam(':fat', $ingredient['fat']);
            $stmt->bindParam(':glycemicIndex', $ingredient['glycemicIndex']);
            $stmt->bindParam(':currentCalorie', $ingredient['currentCalorie']);
            $stmt->bindParam(':currentProtein', $ingredient['currentProtein']);
            $stmt->bindParam(':currentCarb', $ingredient['currentCarb']);
            $stmt->bindParam(':currentFat', $ingredient['currentFat']);

            $allergens = json_encode($ingredient["allergens"]);
            $stmt->bindParam(':allergens', $allergens);

            $ingredientUnits = json_encode($ingredient["ingredientUnits"]);
            $stmt->bindParam(':ingredientUnits', $ingredientUnits);

            $stmt->bindParam(':recipeRefId', $lastInsertedId);

            $stmt->execute();
        }
    }


    public function getRecipeById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `recipes` WHERE `recipeId` = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

        $ingredients = $this->getIngredientsByRecipeId($recipe["recipeId"]);
        $steps = $this->getStepsByRecipeId($recipe["recipeId"]);
        $images= $this->getRecipeImagesById($recipe["recipeId"]);
        
        $recipe["steps"] = $steps;
        $recipe["ingredients"] = $ingredients;
        $recipe["images"] = $images;

        return $recipe;
    }

    private function getStepsByRecipeId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `recipe_steps` WHERE `recipeRefId` = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $steps = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $steps;
    }

    private function getIngredientsByRecipeId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `recipe_ingredients` WHERE `recipeRefId` = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($ingredients as &$ingredient) {
            $ingredient["allergens"] = json_decode($ingredient["allergens"], true);
            $ingredient["ingredientUnits"] = json_decode($ingredient["ingredientUnits"], true);
        }

        return $ingredients;
    }
}
