<?php
require_once 'app/controllers/Ingredient_Controller.php';
$r->addRoute('GET', '/ingredients', [IngredientController::class, 'getIngredientsByUser']);
$r->addRoute('GET', '/ingredient', [IngredientController::class, 'getIngredientForm']);
$r->addRoute('POST', '/ingredient/new', [IngredientController::class, 'addIngredient']);
