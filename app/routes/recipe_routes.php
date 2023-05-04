<?php
    require_once 'app/controllers/Recipe_Controller.php';

    $r->addRoute('GET', '/recipes', [RecipeController::class, 'recipes']);
