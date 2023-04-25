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
        $stmt = $this->pdo->prepare("SELECT * FROM `ingredients` WHERE ingredientName LIKE :name AND (userRefId = :userId OR userRefId IS NULL) OR isAccepted = 1");
        $stmt->bindValue(':name', '%' . $name . '%', PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmt->execute();
        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ingredients;
    }

    public function getIngredient($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `ingredients` WHERE ingredientId  = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ingredientData = $stmt->fetch(PDO::FETCH_ASSOC);

        $ingredient = $this->organizeIngredientUnits($ingredientData);

        if($ingredient) {
           $allergens = $this->getAllergensByIngredientId($ingredient["ingredientId"]);
           if($allergens && !empty($allergens)) {
            $ingredient["allergens"] = $allergens;
           }
        }

        return $ingredient;
    }


    private function getAllergensByIngredientId($ingredientId) {
        $stmt = $this->pdo->prepare("SELECT * FROM `ingredient_allergens` WHERE `ingredientRefId` = :ingredientId");
        $stmt->bindParam(":ingredientId", $ingredientId);;
        $stmt->execute();
        $allergens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $allergens;
    }


    private function organizeIngredientUnits($ingredient)
    {
    
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


        if(isset($ingredient["common_unit"])) {
            $ingredient["ingredientUnits"][] = [
                "index" => 3,
                "unitName" => $ingredient["common_unit"],
                "multiplier" => (int)$ingredient["common_unit_quantity"],
                "common_unit_ex" => $ingredient["common_unit_ex"],
                "isSelected" => false
            ];
        }
        
        return $ingredient;
    }
}
