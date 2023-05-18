<?php
require_once 'app/controllers/Recipe_Controller.php';


//Private
$r->addRoute('GET', '/user/recipes-dashboard', [RecipeController::class, 'recipesDashboard']);
$r->addRoute('GET', '/user/recipe/new', [RecipeController::class, 'recipeForm']);
$r->addRoute('GET', '/user/recipe/delete/{id}', [RecipeController::class, 'deleteRecipe']);
$r->addRoute('GET', '/user/recipe/update/{id}', [RecipeController::class, 'updateRecipeForm']);
$r->addRoute('GET', '/user/recipe/{id}', [RecipeController::class, 'recipeSingle']);


$r->addRoute('POST', '/user/recipe/new', [RecipeController::class, 'addNewRecipe']);
$r->addRoute('POST', '/user/recipe/update/{id}', [RecipeController::class, 'updateRecipe']);


// Public

$r->addRoute('GET', '/recipes', [RecipeController::class, 'renderRecipes']);
