<?php
require_once 'app/api/controllers/diary_ingredient_api.php';
$r->addRoute('GET', '/api/search/{name}', [API::class, 'search']);
$r->addRoute('GET', '/api/ingredient-single/{id}', [API::class, 'getSingleIngredient']);
