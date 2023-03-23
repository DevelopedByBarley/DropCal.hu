<?php

    class HomeModel{

        private $pdo;

        public function __construct()
        {
            $database = new Database();
            $this->pdo = $database->getConnect(); 
        }

        public function Test() {

            $stmt = $this->pdo->prepare("SELECT * FROM `users`");
            $stmt->execute();
            $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $tests;
        }

        public function addUser($userName) {
            $stmt = $this->pdo->prepare("INSERT INTO `users` VALUES (NULL, ?);");
            $stmt->execute([$userName]);
        }

    }
