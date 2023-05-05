<?php
require_once 'app/controllers/Recipe_Controller.php';


//Private
$r->addRoute('GET', '/user/recipes-dashboard', [RecipeController::class, 'recipesDashboard']);
$r->addRoute('GET', '/user/recipe/new', [RecipeController::class, 'recipeForm']);
$r->addRoute('GET', '/user/recipe/delete/{id}', [RecipeController::class, 'deleteRecipe']);




// Public

$r->addRoute('GET', '/recipes', [RecipeController::class, 'renderRecipes']);
