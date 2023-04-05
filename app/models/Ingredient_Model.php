<?php
    class IngredientModel extends DiaryModel{
        public function __construct()
        {
            parent::__construct();
        }

        public function getIngredients($body, $userId) {
            $stmt = $this->pdo->prepare("SELECT * FROM `ingredients` WHERE userRefId = :userId");
            $stmt->bindParam(":userId" , $userId);
            $stmt->execute();
            $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $ingredients;
        }
    }
