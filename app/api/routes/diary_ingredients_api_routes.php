<?php
require_once 'app/api/controllers/api_controller.php';
$r->addRoute('GET', '/api/search/{name}', [APIController::class, 'search']);
$r->addRoute('GET', '/api/ingredient-single/{id}', [APIController::class, 'getSingleIngredient']);
