<?php
$recipe = $params["recipe"] ?? null;
?>

    <div class="row" id="header">
        <div class="col-12" style="min-height: 500px; background-image: url(<?php echo isset($recipe['images']) ? "/public/assets/recipe_images/" . $recipe['images'][0]["r_imageName"] : 'https://i.imgur.com/BrotgYi.jpg'; ?>);background-position: center center; background-size: cover;"></div>
    </div>

    <div class="row">
        <div class="col-12 mt-5 text-center border">
            <h1 class="display-3"><?= $recipe["recipe_name"] ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-2 d-flex align-items-center justify-content-center mt-5 text-center">
            <div>
                <img src="/public/assets/icons/kcal.png" style="height: 50px; width: 50px;" />
                <h4 class="mt-3"><?= $recipe["calorie"] ?></h4>
            </div>
        </div>

        <div class="col-12 col-sm-2 d-flex align-items-center justify-content-center mt-5 text-center">
            <div>
                <img src="/public/assets/icons/GI.png" style="height: 50px; width: 50px;" />
                <h4 class="mt-3"><?= $recipe["glycemic_index"] ?></h4>
            </div>
        </div>
        <div class="col-12 col-sm-4 d-flex align-items-center justify-content-center mt-5 text-center">
            <div>
                <img src="/public/assets/icons/allergens.png" style="height: 50px; width: 50px;" />
                <h4 class="mt-3"><?= $recipe["allergens"] ?></h4>
            </div>
        </div>
        <div class="col-12 col-sm-2 d-flex align-items-center justify-content-center mt-5 text-center">
            <div>
                <img src="/public/assets/icons/diet.png" style="height: 50px; width: 50px;" />
                <h4 class="mt-3"><?= $recipe["diet"] ?></h4>
            </div>
        </div>
        <div class="col-12 col-sm-2 d-flex align-items-center justify-content-center mt-5 text-center">
            <div>
                <img src="/public/assets/icons/clock.png" style="height: 50px; width: 50px;" />
                <h4 class="mt-3"><?= $recipe["meal"] ?></h4>
            </div>
        </div>
    </div>


    <div class="row mt-5">
        <div class="col-12">
            <h1 class="display-3 mt-5 mb-3 border p-2">Hozzávalók</h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <ul class="list-group">
                    <?php foreach ($recipe["ingredients"] as $ingredient) : ?>
                        <li class="list-group-item active mt-1 mb-1" aria-current="true"><?= $ingredient["ingredientName"] ?></li>
                    <?php endforeach ?>
                </ul>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h1 class="display-3 mt-5 mb-3 border p-2">Lépések</h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <ul class="list-group">
                    <?php foreach ($recipe["steps"] as $step) : ?>
                        <li class="list-group-item active mt-1 mb-1" aria-current="true"><?= $step["content"] ?></li>
                    <?php endforeach ?>
                </ul>
            </div>

        </div>
    </div>

    <div class="row mt-5">
        <?php if(count($recipe["images"]) > 1):?>
            <?php foreach ($recipe["images"] as $image) : ?>
            <div class="col-12 col-sm-3" style="min-height: 300px; background-image: url(<?php echo isset($recipe['images']) ? "/public/assets/recipe_images/" . $image["r_imageName"] : 'https://i.imgur.com/BrotgYi.jpg'; ?>);background-position: center center; background-size: cover;"></div>
        <?php endforeach ?>
        <?php endif?>
    </div>

    <div class="row">
        <div class="col-12">
            <h1 class="display-4 mt-5 mb-3 border p-2">Megjegyzés</h1>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sint nostrum repellendus provident, consequatur totam vero modi corrupti aperiam, quod quae velit quos nesciunt molestias? Quas maxime perferendis nobis inventore quo?</p>
        </div>
    </div>
