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

        $stmt = $this->pdo->prepare("SELECT `ingredientRefId` FROM  `recipes` WHERE recipeId = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $ingredientData = $stmt->fetch(PDO::FETCH_ASSOC);
        $ingredientRefId = $ingredientData["ingredientRefId"];



        $stmt = $this->pdo->prepare("DELETE FROM  `ingredients` WHERE ingredientId = :id");
        $stmt->bindParam(":id", $ingredientRefId);
        $isSuccess = $stmt->execute();

        $stmt = $this->pdo->prepare("DELETE FROM  `recipes` WHERE recipeId = :id");
        $stmt->bindParam(":id", $id);
        $isSuccess = $stmt->execute();



        return $isSuccess;
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

    private function updateIngredientByRecipe($body, $id, $macros)
    {
        $ingredientName = $body["name"];
        $ingredientCategorie =  $body["ingredientCategorie"];
        $unit = $body["unit"] === '100g' ? substr($body["unit"], -1) : substr($body["unit"], -2); // Az utolsó karakter (a "g") eltávolítása
        $unit_quantity = substr($body["unit"], 0, -1); // Az utolsó karakter (a "g") kivágása
        $calorie = (int)$body["calorie"];
        $common_unit =  isset($body["common_unit"]) ?  $body["common_unit"] : "";
        $common_unit_quantity = isset($body["common_unit_quantity"]) ? (int)$body["common_unit_quantity"] : 0;
        $common_unit_ex = isset($common_unit) ? $unit : '';
        $calorie = $macros["sumOfCalorie"];
        $protein = $macros["sumOfProtein"];
        $carb = $macros["sumOfCarb"];
        $fat = $macros["sumOfFat"];
        $glycemicIndex = $body["glychemicIndex"] !== '' ? (int)$body["glychemicIndex"] : null;
        $isRecommended = isset($body["isRecommended"]) && $body["isRecommended"] !== "" ? 1 : 0;
        $isAccepted = $isRecommended === 0 ? null : 0;
        $isFromRecipe = 1;

        $stmt = $this->pdo->prepare("SELECT `ingredientRefId` FROM  `recipes` WHERE recipeId = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $ingredientData = $stmt->fetch(PDO::FETCH_ASSOC);
        $ingredientRefId = $ingredientData["ingredientRefId"];

        $stmt = $this->pdo->prepare("UPDATE `ingredients` SET 
        `ingredientName` = :ingredientName, 
        `ingredientCategorie` = :ingredientCategorie, 
        `unit` = :unit, 
        `unit_quantity` = :unit_quantity, 
        `calorie` = :calorie, 
        `common_unit` = :common_unit, 
        `common_unit_quantity` = :common_unit_quantity,
        `common_unit_ex` = :common_unit_ex,
        `protein` = :protein, 
        `carb` = :carb, 
        `fat` = :fat, 
        `glycemicIndex` = :glycemicIndex, 
        `isRecommended` = :isRecommended, 
        `isAccepted` = :isAccepted, 
        `isFromRecipe` = :isFromRecipe 
        WHERE 
        `ingredients`.`ingredientId` = :ingredientId;");

        $stmt->bindParam(':ingredientName', $ingredientName, PDO::PARAM_STR);
        $stmt->bindParam(':ingredientCategorie', $ingredientCategorie, PDO::PARAM_STR);
        $stmt->bindParam(':unit', $unit, PDO::PARAM_STR);
        $stmt->bindParam(':unit_quantity', $unit_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':calorie', $calorie, PDO::PARAM_INT);
        $stmt->bindParam(':common_unit', $common_unit, PDO::PARAM_STR);
        $stmt->bindParam(':common_unit_quantity', $common_unit_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':common_unit_ex', $common_unit_ex, PDO::PARAM_STR);
        $stmt->bindParam(':protein', $protein, PDO::PARAM_INT);
        $stmt->bindParam(':carb', $carb, PDO::PARAM_INT);
        $stmt->bindParam(':fat', $fat, PDO::PARAM_INT);
        $stmt->bindParam(':glycemicIndex', $glycemicIndex, PDO::PARAM_INT);
        $stmt->bindParam(':isRecommended', $isRecommended, PDO::PARAM_INT);
        $stmt->bindParam(':isAccepted', $isAccepted, PDO::PARAM_INT);
        $stmt->bindParam(':isFromRecipe', $isFromRecipe, PDO::PARAM_INT);
        $stmt->bindParam(':ingredientId', $ingredientRefId, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function getRecipeByIngredientId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `recipes` WHERE `ingredientRefId` = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

        return $recipe;
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
        $isForDiab = $body["isForDiab"] === 'on' ? 1 : 0;
        $video = isset($body["video"]) ? $body["video"] : null;
        $description = $body["description"];
        $isRecommended = isset($body["isRecommended"]) && $body["isRecommended"] === 'on' ? 1 : 0;
        $isAccepted = isset($isRecommended) ? 0 : "";


        $this->updateIngredientByRecipe($body, $recipeId, $macros);


        $stmt = $this->pdo->prepare(
            "UPDATE `recipes` SET 
            `recipe_name` = :recipe_name,
            `calorie` = :calorie,
            `protein` = :protein,
            `carb` = :carb,
            `fat` = :fat,
            `glycemic_index` = :glycemic_index,
            `allergens` = :allergens,
            `isForDiab` = :isForDiab,
            `video` = :video,
            `description` = :description,
            `isRecommended` = :isRecommended,
            `isAccepted` = :isAccepted,
            `userRefId` = :userRefId 
            WHERE `recipeId` = :recipeId;"
        );

        $stmt->bindParam(':recipe_name', $recipe_name, PDO::PARAM_STR);
        $stmt->bindParam(':calorie', $calorie, PDO::PARAM_INT);
        $stmt->bindParam(':protein', $protein, PDO::PARAM_INT);
        $stmt->bindParam(':carb', $carb, PDO::PARAM_INT);
        $stmt->bindParam(':fat', $fat, PDO::PARAM_INT);
        $stmt->bindParam(':glycemic_index', $GI, PDO::PARAM_INT);
        $stmt->bindParam(':allergens', $allergens, PDO::PARAM_STR);
        $stmt->bindParam(':isForDiab', $isForDiab, PDO::PARAM_STR);
        $stmt->bindParam(':video', $video, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':isRecommended', $isRecommended, PDO::PARAM_INT);
        $stmt->bindParam(':isAccepted', $isAccepted, PDO::PARAM_INT);
        $stmt->bindParam(':userRefId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);

        $isSuccess = $stmt->execute();



        if ($isSuccess) {
            $this->updateRecipeSteps($steps, $recipeId);
            $this->updateRecipeIngredients($recipeIngredients, $recipeId);
            $this->updateRecipeDiets($diet, $recipeId);
            $this->updateRecipeMeals($meal, $recipeId);

            if ($files["files"]["name"][0] !== "") $this->updateRecipeImages($files, $recipeId);
        }
    }

    private function updateRecipeDiets($diets, $recipeId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `recipe_diets` WHERE `recipeRefId` = :recipeRefId");
        $stmt->bindParam(":recipeRefId", $recipeId);
        $stmt->execute();

        foreach ($diets as $diet) {
            $stmt = $this->pdo->prepare("INSERT INTO `recipe_diets` (`r_dietId`, `r_diet`, `recipeRefId`) VALUES (NULL, :r_diet, :recipeRefId);");
            $stmt->bindParam(":r_diet", $diet);
            $stmt->bindParam(":recipeRefId", $recipeId);

            $stmt->execute();
        }
    }
    private function updateRecipeMeals($meals, $recipeId)
    {

        $stmt = $this->pdo->prepare("DELETE FROM `recipe_meals` WHERE `recipeRefId` = :recipeRefId");
        $stmt->bindParam(":recipeRefId", $recipeId);
        $stmt->execute();

        foreach ($meals as $meal) {
            $stmt = $this->pdo->prepare("INSERT INTO `recipe_meals` (`r_mealId`, `r_meal`, `recipeRefId`) VALUES (NULL, :r_meal, :recipeRefId);");
            $stmt->bindParam(":r_meal", $meal);
            $stmt->bindParam(":recipeRefId", $recipeId);

            $stmt->execute();
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

        $macros = json_decode($body["macros"], true);
        $ingredientInsertId = $this->insertIngredientByRecipe($body, $macros);
        $recipeAllergens = json_decode($body["allergens"], true);
        $ret = [];
        foreach (ALLERGENS as $allergen) {
            foreach ($recipeAllergens as $recipeAllergen) {
                if ($recipeAllergen === $allergen["allergenId"]) {
                    $ret[] = $allergen;
                }
            }
        }


        if ($ret && !empty($ret)) {
            foreach ($ret as $allergen) {
                $stmt = $this->pdo->prepare("INSERT INTO `ingredient_allergens` VALUES (NULL, :allergenNumber, :allergenName, :ingredientRefId);");
                $stmt->bindParam(":allergenNumber", $allergen["allergenId"]);
                $stmt->bindParam(":allergenName", $allergen["allergenName"]);
                $stmt->bindParam(":ingredientRefId", $ingredientInsertId);
                $stmt->execute();
            }
        }




        $recipeIngredients = json_decode($body["ingredients"], true);
        $steps = $body["steps"];
        $recipe_name = $body["name"];
        $meal = isset($body["meal"]) ? $body["meal"] : null;
        $diet = isset($body["diet"]) ? $body["diet"] : null;
        $calorie = $macros["sumOfCalorie"];
        $protein = $macros["sumOfProtein"];
        $carb = $macros["sumOfCarb"];
        $fat = $macros["sumOfFat"];
        $GI = $body["glycemic_index_summary"];
        $allergens = $body["allergens"];
        $isForDiab = (isset($body["isForDiab"]) && $body["isForDiab"]) === 'on' ? 1 : 0;
        $video = isset($body["video"]) ? $body["video"] : null;
        $description = $body["description"];
        $isRecommended = isset($body["isRecommended"]) && $body["isRecommended"] === 'on' ? 1 : 0;
        $isAccepted = isset($isRecommended) ? 0 : "";


        $stmt = $this->pdo->prepare(
            "INSERT INTO 
        `recipes` 
        VALUES 
        (NULL, 
        :recipe_name, 
        :calorie, 
        :protein, 
        :carb, 
        :fat, 
        :glycemic_index, 
        :allergens,
        :isForDiab,
        :video,
        :description,
        :isRecommended, 
        :isAccepted, 
        :userRefId,
        :ingredientRefId
        );"
        );


        $stmt->bindParam(':recipe_name', $recipe_name, PDO::PARAM_STR);
        $stmt->bindParam(':calorie', $calorie, PDO::PARAM_INT);
        $stmt->bindParam(':protein', $protein, PDO::PARAM_INT);
        $stmt->bindParam(':carb', $carb, PDO::PARAM_INT);
        $stmt->bindParam(':fat', $fat, PDO::PARAM_INT);
        $stmt->bindParam(':glycemic_index', $GI, PDO::PARAM_INT);
        $stmt->bindParam(':allergens', $allergens, PDO::PARAM_STR);
        $stmt->bindParam(':isForDiab', $isForDiab, PDO::PARAM_STR);
        $stmt->bindParam(':video', $video, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':isRecommended', $isRecommended, PDO::PARAM_INT);
        $stmt->bindParam(':isAccepted', $isAccepted, PDO::PARAM_INT);
        $stmt->bindParam(':userRefId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':ingredientRefId', $ingredientInsertId, PDO::PARAM_INT);

        $stmt->execute();

        $lastInsertedId = $this->pdo->lastInsertId();

        if ($lastInsertedId) {
            $recipeImages = $this->fileSaver->saver($files["files"], "/recipe_images", null);
            $this->insertRecipeImages($recipeImages,  $lastInsertedId);
            $this->insertRecipeIngredients($recipeIngredients, $lastInsertedId);
            $this->insertRecipeSteps($steps, $lastInsertedId);
            $this->insertRecipeDiets($diet, $lastInsertedId);
            $this->insertRecipeMeals($meal, $lastInsertedId);
        }
    }

    private function insertIngredientByRecipe($body, $macros)
    {
        $ingredientName = $body["name"];
        $ingredientCategorie =  $body["ingredientCategorie"];
        $unit = $body["unit"] === '100g' ? substr($body["unit"], -1) : substr($body["unit"], -2); // Az utolsó karakter (a "g") eltávolítása
        $unit_quantity = substr($body["unit"], 0, -1); // Az utolsó karakter (a "g") kivágása
        $common_unit =  isset($body["common_unit"]) ?  $body["common_unit"] : "";
        $common_unit_quantity = isset($body["common_unit_quantity"]) ? (int)$body["common_unit_quantity"] : 0;
        $common_unit_ex = isset($common_unit) ? $unit : '';
        $calorie = $macros["sumOfCalorie"];
        $protein = $macros["sumOfProtein"];
        $carb = $macros["sumOfCarb"];
        $fat = $macros["sumOfFat"];
        $GI = $body["glycemic_index_summary"];
        $isRecommended = isset($body["isRecommended"]) && $body["isRecommended"] === 'on' ? 1 : 0;
        $isAccepted = isset($isRecommended) ? 0 : "";
        $isFromRecipe = 1;
        $userRefId = $_SESSION["userId"] ?? null;




        $stmt = $this->pdo->prepare("INSERT INTO `ingredients` VALUES 
        (NULL, 
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
        :isRecommended, 
        :isAccepted, 
        :isFromRecipe, 
        :userRefId);
         ");

        $stmt->bindParam(':ingredientName', $ingredientName, PDO::PARAM_STR);
        $stmt->bindParam(':ingredientCategorie', $ingredientCategorie, PDO::PARAM_STR);
        $stmt->bindParam(':unit', $unit, PDO::PARAM_STR);
        $stmt->bindParam(':unit_quantity', $unit_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':calorie', $calorie, PDO::PARAM_INT);
        $stmt->bindParam(':common_unit', $common_unit, PDO::PARAM_STR);
        $stmt->bindParam(':common_unit_quantity', $common_unit_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':common_unit_ex', $common_unit_ex, PDO::PARAM_STR);
        $stmt->bindParam(':protein', $protein, PDO::PARAM_INT);
        $stmt->bindParam(':carb', $carb, PDO::PARAM_INT);
        $stmt->bindParam(':fat', $fat, PDO::PARAM_INT);
        $stmt->bindParam(':glycemicIndex', $GI, PDO::PARAM_INT);
        $stmt->bindParam(':isRecommended', $isRecommended, PDO::PARAM_INT);
        $stmt->bindParam(':isAccepted', $isAccepted, PDO::PARAM_INT);
        $stmt->bindParam(':isFromRecipe', $isFromRecipe, PDO::PARAM_INT);
        $stmt->bindParam(':userRefId', $userRefId, PDO::PARAM_INT);

        $stmt->execute();

        return $this->pdo->lastInsertId();
    }


    private  function insertRecipeDiets($diets, $lastInsertedId)
    {
        foreach ($diets as $diet) {
            $stmt = $this->pdo->prepare("INSERT INTO `recipe_diets` (`r_dietId`, `r_diet`, `recipeRefId`) VALUES (NULL, :r_diet, :recipeRefId);");
            $stmt->bindParam(":r_diet", $diet);
            $stmt->bindParam(":recipeRefId", $lastInsertedId);

            $stmt->execute();
        }
    }


    private function insertRecipeMeals($meals, $lastInsertedId)
    {
        foreach ($meals as $meal) {
            $stmt = $this->pdo->prepare("INSERT INTO `recipe_meals` (`r_mealId`, `r_meal`, `recipeRefId`) VALUES (NULL, :r_meal, :recipeRefId);");
            $stmt->bindParam(":r_meal", $meal);
            $stmt->bindParam(":recipeRefId", $lastInsertedId);

            $stmt->execute();
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

    private function getIngredientDataForRecipe($ingredientRefId)
    {
        $stmt = $this->pdo->prepare("SELECT `ingredientCategorie`,`unit`, `unit_quantity`, `common_unit`, `common_unit_quantity`, `common_unit_ex` FROM `ingredients` WHERE `ingredientId` = :id");
        $stmt->bindParam(":id", $ingredientRefId);
        $stmt->execute();
        $ingredient = $stmt->fetch(PDO::FETCH_ASSOC);
        $ingredient["ingredientUnit"] = $ingredient["unit_quantity"] . "" . $ingredient["unit"];

        return $ingredient;
    }

    public function getRecipeById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `recipes` WHERE `recipeId` = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
        $ingredientRefId = $recipe["ingredientRefId"];

        if (isset($ingredientRefId) && $ingredientRefId !== '') {
            $recipe["ingredientData"] = $this->getIngredientDataForRecipe($ingredientRefId);
        }

        $ingredients = $this->getIngredientsByRecipeId($recipe["recipeId"]);
        $steps = $this->getStepsByRecipeId($recipe["recipeId"]);
        $images = $this->getRecipeImagesById($recipe["recipeId"]);
        $diets = $this->getRecipeDietsById($recipe["recipeId"]);
        $meals = $this->getRecipeMealsById($recipe["recipeId"]);


        $recipe["steps"] = $steps;
        $recipe["ingredients"] = $ingredients;
        $recipe["diets"] = $diets;
        $recipe["meals"] = $meals;
        $recipe["images"] = $images;

        return $recipe;
    }

    private function getRecipeDietsById($recipeRefId)
    {
        $ret = [];
        $stmt = $this->pdo->prepare("SELECT * FROM `recipe_diets` WHERE `recipeRefId` = :id");
        $stmt->bindParam(":id", $recipeRefId);
        $stmt->execute();
        $diets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($diets as $diet) {
            $ret[] = $diet["r_diet"];
        }
        return $ret;
    }

    private function getRecipeMealsById($recipeRefId)
    {
        $ret = [];
        $stmt = $this->pdo->prepare("SELECT `r_meal` FROM `recipe_meals` WHERE `recipeRefId` = :id");
        $stmt->bindParam(":id", $recipeRefId);
        $stmt->execute();
        $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($meals as $meal) {
            $ret[] = $meal["r_meal"];
        }

        return $ret;
    }

    private function getStepsByRecipeId($recipeRefId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `recipe_steps` WHERE `recipeRefId` = :id");
        $stmt->bindParam(":id", $recipeRefId);
        $stmt->execute();
        $steps = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $steps;
    }

    private function getIngredientsByRecipeId($recipeRefId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `recipe_ingredients` WHERE `recipeRefId` = :id");
        $stmt->bindParam(":id", $recipeRefId);
        $stmt->execute();
        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($ingredients as &$ingredient) {
            $ingredient["allergens"] = json_decode($ingredient["allergens"], true);
            $ingredient["ingredientUnits"] = json_decode($ingredient["ingredientUnits"], true);
        }

        return $ingredients;
    }
}
