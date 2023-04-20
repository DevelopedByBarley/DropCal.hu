<?php
require_once 'app/controllers/Ingredient_Controller.php';
$r->addRoute('GET', '/ingredients', [IngredientController::class, 'getIngredientsByUser']);
$r->addRoute('GET', '/ingredient', [IngredientController::class, 'getIngredientForm']);
$r->addRoute('GET', '/ingredient/delete/{id}', [IngredientController::class, 'deleteIngredient']);
$r->addRoute('GET', '/ingredient/update', [IngredientController::class, 'getIngredientForm']);



$r->addRoute('POST', '/ingredient/new', [IngredientController::class, 'addIngredient']);
$r->addRoute('POST', '/ingredient/update/{id}', [IngredientController::class, 'updateIngredient']);
