<?php
require_once 'app/api/controllers/api_controller.php';
$r->addRoute('GET', '/api/search/{name}', [APIController::class, 'search']);
$r->addRoute('GET', '/api/ingredient-single/{id}', [APIController::class, 'getSingleIngredient']);
$r->addRoute('GET', '/api/diary-ingredient/{id}', [APIController::class, 'getDiaryIngredient']);
$r->addRoute('POST', '/api/ingredient-new', [APIController::class, 'addDiaryIngredient']);
$r->addRoute('POST', '/api/ingredient-update/{id}', [APIController::class, 'updateDiaryIngredient']);
