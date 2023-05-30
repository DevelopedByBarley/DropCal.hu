<?php

define('PARTS_OF_THE_DAY', [
    [
        'id' => 0,
        'value' => 'Reggeli',
        'color' => 'warning',
        'name' => 'breakfast',
        'isSelected' => false,
    ],
    [
        'id' => 1,
        'value' => 'Tízórai',
        'color' => 'info',
        'name' => 'snack_1',
        'isSelected' => false,
    ],
    [
        'id' => 2,
        'value' => 'Ebéd',
        'color' => 'success',
        'name' => 'lunch',
        'isSelected' => false,
    ],
    [
        'id' => 3,
        'value' => 'Uzsonna',
        'color' => 'primary',
        'name' => 'snack_2',
        'isSelected' => false,
    ],
    [
        'id' => 4,
        'value' => 'Vacsora',
        'color' => 'secondary',
        'name' => 'dinner',
        'isSelected' => false,
    ],
    [
        'id' => 5,
        'value' => 'Nasi',
        'color' => 'danger',
        'name' => 'snack',
        'isSelected' => false,
    ],
]);

define('ALLERGENS', [
    [
        "allergenName" => "Glutén",
        "allergenId" => 1,
    ],
    [
        "allergenName" => "Rákfélék",
        "allergenId" => 2,
    ],
    [
        "allergenName" => "Tojás",
        "allergenId" => 3,
    ],
    [
        "allergenName" => "Halak",
        "allergenId" => 4,
    ],
    [
        "allergenName" => "Földimogyoró",
        "allergenId" => 5,
    ],
    [
        "allergenName" => "Szójabab",
        "allergenId" => 6,
    ],
    [
        "allergenName" => "Tej",
        "allergenId" => 7,
    ],
    [
        "allergenName" => "Diófélék",
        "allergenId" => 8,
    ],
    [
        "allergenName" => "Zeller",
        "allergenId" => 9,
    ],
    [
        "allergenName" => "Mustár",
        "allergenId" => 10,
    ],
    [
        "allergenName" => " Szezámmag és abból készült termékek",
        "allergenId" => 11,
    ],
    [
        "allergenName" => "Kén-dioxid és SO2 -ben kifejezett szulfitok",
        "allergenId" => 12,
    ],
    [
        "allergenName" => "Csillagfürt és abból készült termékek",
        "allergenId" => 13,
    ],
    [
        "allergenName" => "Puhatestűek",
        "allergenId" => 14,
    ],
]);


define('INGREDIENT_CATEGORIES', [
    "Fagylalt", "Jégkrém", "Gomba", "Gabona", "Gyümölcs", "Hal", "Húskészítmény", "Ital", "Készétel", "Köret", "Leves", "Olaj", "Pékáru", "Édesség", "Sütemény", "Rágcsa", "Tészta", "Tejtermék"
]);

define('UNITS', [
    "100g", "100ml"
]);

define('COMMON_UNITS', [
    "Darab", "Bögre", "Csésze", "Szelet", "Marék", "Evőkanál", "Zacskó", "Csipet", "Csomag", "Doboz", "Üveg", "Tubus", "Gerezd"
]);


define("MEALS", [
    "Reggeli",
    "Tizórai",
    "Ebéd",
    "Vacsora",
    "Uzsonna"
]);

define("DIETS", [
    "Általános",
    "Húsimádó",
    "Vegetáriánus",
    "Vegán",
    "Paleo",
    "Ketogén"
]);
