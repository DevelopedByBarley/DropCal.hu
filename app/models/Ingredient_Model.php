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
        $unit = $body["unit"] === '100g' ? substr($body["unit"], -1) : substr($body["unit"], -2); // Az utolsó karakter (a "g") eltávolítása
        $unit_quantity = substr($body["unit"], 0, -1); // Az utolsó karakter (a "g") kivágása
        $calorie = (int)$body["calorie"];
        $common_unit =  isset($body["common_unit"]) ?  $body["common_unit"] : "";
        $common_unit_quantity = isset($body["common_unit_quantity"]) ? (int)$body["common_unit_quantity"] : 0;
        $common_unit_ex = isset($common_unit) ? $unit : '';
        $calorie = (int)$body["calorie"];
        $protein = (int)$body["protein"];
        $carb = (int)$body["carb"];
        $fat = (int)$body["fat"];
        $glycemicIndex = $body["glychemicIndex"] !== '' ? (int)$body["glychemicIndex"] : null;
        $isRecommended = isset($body["isRecommended"]) && $body["isRecommended"] !== "" ? 1 : 0;
        $isAccepted = $isRecommended === 0 ? null : 0;
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
        $stmt->bindParam(':glycemicIndex', $glycemicIndex, PDO::PARAM_INT);
        $stmt->bindParam(':isRecommended', $isRecommended, PDO::PARAM_INT);
        $stmt->bindParam(':isAccepted', $isAccepted, PDO::PARAM_INT);
        $stmt->bindParam(':userRefId', $userRefId, PDO::PARAM_INT);

        $stmt->execute();

        $lastInsertedId = $this->pdo->lastInsertId();

        if ($allergens) {

            foreach ($allergens as $allergen) {
                $stmt = $this->pdo->prepare("INSERT INTO `ingredient_allergens` VALUES (NULL, :allergenNumber, :allergenName, :ingredientRefId);");
                $stmt->bindParam(":allergenNumber", $allergen["allergenId"]);
                $stmt->bindParam(":allergenName", $allergen["allergenName"]);
                $stmt->bindParam(":ingredientRefId", $lastInsertedId);
                $stmt->execute();
            }
        }

        if ($this->pdo->lastInsertId()) {
            return ([
                "state" => true
            ]
            );
        }
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `ingredients` WHERE `ingredientId` = :id");
        $stmt->bindParam(":id", $id);
        $isSuccess = $stmt->execute();

        if ($isSuccess) {
            header("Location: /ingredients");
        }
    }

    public function getIngredientById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `ingredients` WHERE `ingredientId` = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $ingredient = $stmt->fetch(PDO::FETCH_ASSOC);

        $allergens = $this->getAllergensByIngredientId($ingredient["ingredientId"]);

        if ($allergens) {
            $ingredient["allergens"] = $allergens;
        }

        return $ingredient;
    }

    public function updateIngredient($ingredientId, $body)
    {

        

        $allergens = json_decode($body["allergens"], true);
        $ingredientName = $body["ingredientName"];
        $ingredientCategorie =  $body["ingredientCategorie"];
        $unit = $body["unit"] === '100g' ? substr($body["unit"], -1) : substr($body["unit"], -2); // Az utolsó karakter (a "g") eltávolítása
        $unit_quantity = substr($body["unit"], 0, -1); // Az utolsó karakter (a "g") kivágása
        $calorie = (int)$body["calorie"];
        $common_unit =  isset($body["common_unit"]) ?  $body["common_unit"] : "";
        $common_unit_quantity = isset($body["common_unit_quantity"]) ? (int)$body["common_unit_quantity"] : 0;
        $common_unit_ex = isset($common_unit) ? $unit : '';
        $calorie = (int)$body["calorie"];
        $protein = (int)$body["protein"];
        $carb = (int)$body["carb"];
        $fat = (int)$body["fat"];
        $glycemicIndex = (int)$body["glychemicIndex"];
        $isRecommended = isset($body["isRecommended"]) && $body["isRecommended"] !== "" ? 1 : 0;
        $isAccepted = $isRecommended === 0 ? null : 0;



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
        `isAccepted` = :isAccepted 
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
        $stmt->bindParam(':ingredientId', $ingredientId, PDO::PARAM_INT);

        $stmt->execute();

        if (empty($allergens)) {
            $this->deleteAllergens($ingredientId);
            return ([
                "state" => true
            ]);
        }

        if ($allergens) {

            $isSuccess = $this->deleteAllergens($ingredientId);
            foreach ($allergens as $allergen) {
                if ($isSuccess) {
                    $stmt = $this->pdo->prepare("INSERT INTO `ingredient_allergens` VALUES (NULL, :allergenNumber, :allergenName, :ingredientRefId);");
                    $stmt->bindParam(":allergenNumber", $allergen["allergenId"]);
                    $stmt->bindParam(":allergenName", $allergen["allergenName"]);
                    $stmt->bindParam(":ingredientRefId", $ingredientId);
                    $stmt->execute();
                }
            }
        }


        return ([
            "state" => true
        ]);
    }

    private function deleteAllergens($ingredientId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `ingredient_allergens` WHERE `ingredientRefId` = :ingredientId");
        $stmt->bindParam(":ingredientId", $ingredientId);
        $isSuccess = $stmt->execute();

        return $isSuccess;
    }


    private function getAllergensByIngredientId($ingredientId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `ingredient_allergens` WHERE `ingredientRefId` = :ingredientId");
        $stmt->bindParam(":ingredientId", $ingredientId);;
        $stmt->execute();
        $allergens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $allergens;
    }
}
