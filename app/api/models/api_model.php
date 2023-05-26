<?php
class APIModel
{
    protected $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnect();
    }

    public function searchIngredient($userId, $name)
    {
        $limit = 7;
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare("SELECT * FROM `ingredients` WHERE ingredientName LIKE :name AND (userRefId = :userId OR userRefId IS NULL OR isAccepted = 1) LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':name', '%' . $name . '%', PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // GET all page counter

        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM `ingredients` WHERE ingredientName LIKE :name AND (userRefId = :userId OR userRefId IS NULL AND isAccepted = 1)");
        $stmt->bindValue(':name', '%' . $name . '%', PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmt->execute();
        $ingredientCounterData = $stmt->fetch(PDO::FETCH_ASSOC);
        $ingredientCount = (int)$ingredientCounterData["count"];

   
        $counter = 0;

        for ($i = 0; $i < $ingredientCount; $i += $limit) {
            $counter++;
        }


        
  
        return [
            "ingredients" => $ingredients,
            "number_of_page" => $counter
        ];
    }

    public function getIngredient($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `ingredients` WHERE ingredientId  = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ingredientData = $stmt->fetch(PDO::FETCH_ASSOC);

        $ingredient = $this->organizeIngredientUnits($ingredientData);

        if ($ingredient) {
            $allergens = $this->getAllergensByIngredientId($ingredient["ingredientId"]);
            if ($allergens && !empty($allergens)) {
                $ingredient["allergens"] = $allergens;
            }
        }

        return $ingredient;
    }


    private function getAllergensByIngredientId($ingredientId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `ingredient_allergens` WHERE `ingredientRefId` = :ingredientId");
        $stmt->bindParam(":ingredientId", $ingredientId);;
        $stmt->execute();
        $allergens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $allergens;
    }


    private function organizeIngredientUnits($ingredient)
    {
        $selected_unit = isset($ingredient["selected_unit"]) ? json_decode(html_entity_decode($ingredient["selected_unit"]), true) : null;


        if ($ingredient["unit"] === 'g') {
            $ingredient["ingredientUnits"] = [
                [
                    "index" => 0,
                    "unitName" => "g",
                    "multiplier" => 1,
                    "isSelected" => true
                ],
                [
                    "index" => 1,
                    "unitName" => "dkg",
                    "multiplier" => 10,
                    "isSelected" => false
                ],
                [
                    "index" => 2,
                    "unitName" => "kg",
                    "multiplier" => 1000,
                    "isSelected" => false
                ],
            ];
        } else {
            $ingredient["ingredientUnits"] = [
                [
                    "index" => 0,
                    "unitName" => "ml",
                    "multiplier" => 1,
                    "isSelected" => true
                ],
                [
                    "index" => 1,
                    "unitName" => "dl",
                    "multiplier" => 10,
                    "isSelected" => false
                ],
                [
                    "index" => 2,
                    "unitName" => "l",
                    "multiplier" => 1000,
                    "isSelected" => false
                ],
            ];
        }


        if (isset($ingredient["common_unit"])) {
            $ingredient["ingredientUnits"][] = [
                "index" => 3,
                "unitName" => $ingredient["common_unit"],
                "multiplier" => (int)$ingredient["common_unit_quantity"],
                "common_unit_ex" => $ingredient["common_unit_ex"],
                "isSelected" => false
            ];
        }

        if ($selected_unit) {
            for ($i = 0; $i < count($ingredient["ingredientUnits"]); $i++) {
                $unit = $ingredient["ingredientUnits"][$i];
                if ($unit["index"] === $selected_unit["index"]) {
                    $ingredient["ingredientUnits"][$i]["isSelected"] = true;
                } else {
                    $ingredient["ingredientUnits"][$i]["isSelected"] = false;
                }
            }
        }

        return $ingredient;
    }



    public function addIngredient($body)
    {
        $ingredientName = isset($body['name']) ? filter_var($body['name'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $unit = isset($body['unit']) ? filter_var($body['unit'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $unit_quantity = $body['unitQuantity'] !== "" ? filter_var($body['unitQuantity'], FILTER_VALIDATE_INT) : 0;
        $common_unit = isset($body['commonUnit']) ? filter_var($body['commonUnit'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $common_unit_quantity = $body['common_unit_quantity'] !== "" ? filter_var($body['common_unit_quantity'], FILTER_VALIDATE_INT) : 0;
        $common_unit_ex = isset($body['common_unit_ex']) ? filter_var($body['common_unit_ex'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $partOfTheDay = $body['partOfTheDay'] !== "" ? filter_var($body['partOfTheDay'], FILTER_VALIDATE_INT) : 0;
        $selected_unit = isset($body['selectedUnit']) ? filter_var(json_encode($body['selectedUnit']), FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $calorie = $body['calorie'] !== "" ? filter_var($body['calorie'], FILTER_VALIDATE_INT) : 0;
        $protein = $body['protein'] !== "" ? filter_var($body['protein'], FILTER_VALIDATE_INT) : 0;
        $carb = $body['carb'] !== "" ? filter_var($body['carb'], FILTER_VALIDATE_INT) : 0;
        $fat = $body['fat'] !== "" ? filter_var($body['fat'], FILTER_VALIDATE_INT) : 0;
        $glychemicIndex = isset($body['g']) ? filter_var($body['glychemicIndex'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $currentCalorie = $body['currentCalorie'] !== "" ? filter_var($body['currentCalorie'], FILTER_VALIDATE_INT) : 0;;
        $currentProtein = $body['currentProtein'] !== "" ? filter_var($body['currentProtein'], FILTER_VALIDATE_INT) : 0;;
        $currentCarb = $body['currentCarb'] !== "" ? filter_var($body['currentCarb'], FILTER_VALIDATE_INT) : 0;;
        $currentFat = $body['currentFat'] !== "" ? filter_var($body['currentFat'], FILTER_VALIDATE_INT) : 0;;
        $diaryRefId = $body['diaryRefId'] !== "" ? filter_var($body['diaryRefId'], FILTER_VALIDATE_INT) : 0;


        $stmt = $this->pdo->prepare("
            INSERT INTO `diary_ingredients` (
                `d_ingredientId`, 
                `ingredientName`, 
                `partOfTheDay`, 
                `unit`, 
                `unit_quantity`, 
                `calorie`, 
                `common_unit`, 
                `common_unit_quantity`, 
                `common_unit_ex`, 
                `selected_unit`, 
                `protein`, 
                `carb`, 
                `fat`, 
                `glychemicIndex`,
                `current_calorie`,
                `current_protein`,
                `current_carb`,
                `current_fat`, 
                `diaryRefId`
            ) VALUES (
                NULL, 
                :ingredientName, 
                :partOfTheDay, 
                :unit, 
                :unit_quantity, 
                :calorie, 
                :common_unit, 
                :common_unit_quantity, 
                :common_unit_ex, 
                :selected_unit, 
                :protein, 
                :carb, 
                :fat, 
                :glychemicIndex,
                :current_calorie,
                :current_protein,
                :current_carb,
                :current_fat, 
                :diaryRefId
            )
        ");

        $stmt->bindParam(':ingredientName', $ingredientName);
        $stmt->bindParam(':partOfTheDay', $partOfTheDay);
        $stmt->bindParam(':unit', $unit);
        $stmt->bindParam(':unit_quantity', $unit_quantity);
        $stmt->bindParam(':calorie', $calorie);
        $stmt->bindParam(':common_unit', $common_unit);
        $stmt->bindParam(':common_unit_quantity', $common_unit_quantity);
        $stmt->bindParam(':common_unit_ex', $common_unit_ex);
        $stmt->bindParam(':selected_unit', $selected_unit);
        $stmt->bindParam(':protein', $protein);
        $stmt->bindParam(':carb', $carb);
        $stmt->bindParam(':fat', $fat);
        $stmt->bindParam(':glychemicIndex', $glychemicIndex);
        $stmt->bindParam(':current_calorie', $currentCalorie);
        $stmt->bindParam(':current_protein', $currentProtein);
        $stmt->bindParam(':current_carb', $currentCarb);
        $stmt->bindParam(':current_fat', $currentFat);
        $stmt->bindParam(':diaryRefId', $diaryRefId);

        $stmt->execute();
        if ($this->pdo->lastInsertId()) {
            echo json_encode([
                "state" => true
            ]);
        } else {
            echo json_encode([
                "state" => false
            ]);
        }
    }

    public function getDiaryIngredientById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `diary_ingredients` WHERE `d_ingredientId` = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $ingredientData = $stmt->fetch(PDO::FETCH_ASSOC);

        $ingredient = $this->organizeIngredientUnits($ingredientData);

        if ($ingredient) {
            $allergens = $this->getAllergensByIngredientId($ingredient["ingredientId"]);
            if ($allergens && !empty($allergens)) {
                $ingredient["allergens"] = $allergens;
            }
        }

        echo json_encode($ingredient);
    }

    public function updateIngredientForDiary($id, $body)
    {

        $ingredientName = isset($body['name']) ? filter_var($body['name'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $unit = isset($body['unit']) ? filter_var($body['unit'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $unit_quantity = $body['unitQuantity'] !== "" ? filter_var($body['unitQuantity'], FILTER_VALIDATE_INT) : 0;
        $common_unit = isset($body['commonUnit']) ? filter_var($body['commonUnit'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $common_unit_quantity = $body['common_unit_quantity'] !== "" ? filter_var($body['common_unit_quantity'], FILTER_VALIDATE_INT) : 0;
        $common_unit_ex = isset($body['common_unit_ex']) ? filter_var($body['common_unit_ex'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $partOfTheDay = $body['partOfTheDay'] !== "" ? filter_var($body['partOfTheDay'], FILTER_VALIDATE_INT) : 0;
        $selected_unit = isset($body['selectedUnit']) ? filter_var(json_encode($body['selectedUnit']), FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $calorie = $body['calorie'] !== "" ? filter_var($body['calorie'], FILTER_VALIDATE_INT) : 0;
        $protein = $body['protein'] !== "" ? filter_var($body['protein'], FILTER_VALIDATE_INT) : 0;
        $carb = $body['carb'] !== "" ? filter_var($body['carb'], FILTER_VALIDATE_INT) : 0;
        $fat = $body['fat'] !== "" ? filter_var($body['fat'], FILTER_VALIDATE_INT) : 0;
        $glychemicIndex = isset($body['g']) ? filter_var($body['glychemicIndex'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $currentCalorie = $body['currentCalorie'] !== "" ? filter_var($body['currentCalorie'], FILTER_VALIDATE_INT) : 0;;
        $currentProtein = $body['currentProtein'] !== "" ? filter_var($body['currentProtein'], FILTER_VALIDATE_INT) : 0;;
        $currentCarb = $body['currentCarb'] !== "" ? filter_var($body['currentCarb'], FILTER_VALIDATE_INT) : 0;;
        $currentFat = $body['currentFat'] !== "" ? filter_var($body['currentFat'], FILTER_VALIDATE_INT) : 0;;
        //$diaryRefId = $body['diaryRefId'] !== "" ? filter_var($body['diaryRefId'], FILTER_VALIDATE_INT) : 0;

        $stmt = $this->pdo->prepare("UPDATE `diary_ingredients` SET 
        `ingredientName` = :ingredientName, 
        `partOfTheDay` = :partOfTheDay, 
        `unit` = :unit, 
        `unit_quantity` = :unit_quantity, 
        `calorie` = :calorie,
        `common_unit` = :common_unit, 
        `common_unit_quantity` = :common_unit_quantity, 
        `common_unit_ex` = :common_unit_ex, 
        `selected_unit` = :selected_unit,
        `protein` = :protein, 
        `carb` = :carb, 
        `fat` = :fat, 
        `glychemicIndex` = :glychemicIndex, 
        `current_calorie` = :current_calorie,
        `current_protein` = :current_protein, 
        `current_carb` = :current_carb, 
        `current_fat` = :current_fat
        WHERE `diary_ingredients`.`d_ingredientId` = :d_ingredientId;");



        $stmt->bindParam(':ingredientName', $ingredientName);
        $stmt->bindParam(':partOfTheDay', $partOfTheDay);
        $stmt->bindParam(':unit', $unit);
        $stmt->bindParam(':unit_quantity', $unit_quantity);
        $stmt->bindParam(':calorie', $calorie);
        $stmt->bindParam(':common_unit', $common_unit);
        $stmt->bindParam(':common_unit_quantity', $common_unit_quantity);
        $stmt->bindParam(':common_unit_ex', $common_unit_ex);
        $stmt->bindParam(':selected_unit', $selected_unit);
        $stmt->bindParam(':protein', $protein);
        $stmt->bindParam(':carb', $carb);
        $stmt->bindParam(':fat', $fat);
        $stmt->bindParam(':glychemicIndex', $glychemicIndex);
        $stmt->bindParam(':current_calorie', $currentCalorie);
        $stmt->bindParam(':current_protein', $currentProtein);
        $stmt->bindParam(':current_carb', $currentCarb);
        $stmt->bindParam(':current_fat', $currentFat);
        $stmt->bindParam(':d_ingredientId', $id);

        $isSuccess = $stmt->execute();

        return $isSuccess;
    }

    public function deleteIngredientForDiary($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `diary_ingredients` WHERE `d_ingredientId` = :id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
