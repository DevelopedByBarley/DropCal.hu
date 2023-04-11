<?php
class IngredientModel extends DiaryModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIngredients($body, $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `ingredients` WHERE userRefId = :userId");
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($ingredients as $index => $ingredient) {

            $stmt = $this->pdo->prepare("SELECT * FROM `ingredient_allergens` WHERE ingredientRefId = :ingredientId");
            $stmt->bindParam(":ingredientId", $ingredient["ingredientId"]);
            $stmt->execute();
            $allergens = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $ingredients[$index]["allergens"] = $allergens;
        }

        return $ingredients;
    }

    public function addIngredient($body)
    {
        $allergens = json_decode($body["allergens"], true);
        $ingredientName = $body["ingredientName"];
        $ingredientCategorie =  $body["ingredientCategorie"];
        $unit = $body["unit"];
        $calorie = (int)$body["calorie"];
        $protein = (int)$body["protein"];
        $carb = (int)$body["carb"];
        $fat = (int)$body["fat"];
        $glycemicIndex = (int)$body["glychemicIndex"];
        $isRecommended = $body["isRecommended"] === 'on' ? 1 : 0;
        $isAccepted = $isRecommended === 0 ? null : 0;
        $userRefId = $_SESSION["userId"] ?? null;

        $stmt = $this->pdo->prepare("INSERT INTO `ingredients` VALUES 
            (NULL, 
            :ingredientName, 
            :ingredientCategorie, 
            :unit, 
            :calorie, 
            :protein, 
            :carb, 
            :fat,
            :glycemicIndex, 
            :isRecommended, 
            :isAccepted, 
            :userRefId);
             ");

        $stmt->bindParam(':ingredientName', $ingredientName, PDO::PARAM_STR);
        $stmt->bindParam(':ingredientCategorie', $ingredientCategorie, PDO::PARAM_STR);
        $stmt->bindParam(':unit', $unit, PDO::PARAM_STR);
        $stmt->bindParam(':calorie', $calorie, PDO::PARAM_INT);
        $stmt->bindParam(':protein', $protein, PDO::PARAM_INT);
        $stmt->bindParam(':carb', $carb, PDO::PARAM_INT);
        $stmt->bindParam(':fat', $fat, PDO::PARAM_INT);
        $stmt->bindParam(':glycemicIndex', $glycemicIndex, PDO::PARAM_INT);
        $stmt->bindParam(':isRecommended', $isRecommended, PDO::PARAM_INT);
        $stmt->bindParam(':isAccepted', $isAccepted, PDO::PARAM_INT);
        $stmt->bindParam(':userRefId', $userRefId, PDO::PARAM_INT);

        $stmt->execute();

        $lastInsertedId = $this->pdo->lastInsertId();



        foreach ($allergens as $allergen) {
            $stmt = $this->pdo->prepare("INSERT INTO `ingredient_allergens` VALUES (NULL, :allergenNumber, :allergenName, :ingredientRefId);");
            $stmt->bindParam(":allergenNumber", $allergen["allergenId"]);
            $stmt->bindParam(":allergenName", $allergen["allergenName"]);
            $stmt->bindParam(":ingredientRefId", $lastInsertedId);
            $stmt->execute();
        }

        return $this->pdo->lastInsertId() ? true : false;
    }
}
