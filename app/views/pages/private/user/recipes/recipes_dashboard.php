<?php
$recipes = $params["recipes"];

?>

<?php if (empty($recipes)) : ?>
    <div class="row">
        <div class="col-12 d-flex align-items-center justify-content-center flex-column text-center" style="min-height: 50vh;">
            <h1 class="display-2">Jelenleg egyetlen recepted sincs!</h1>
            <div class="mb-5">
                <a href="/user/recipe/new" class="btn btn-outline-dark mt-3">Recept hozzáadása</a>
            </div>
        </div>
    </div>
<?php else : ?>
    <div id="recipe-container" class="row">
        <div class="col-12 mb-3 mt-3 text-center">
            <h1 class="display-3">Receptjeim</h1>
        </div>
        <div class="col-12 mb-5 text-center">
            <a href="/user/recipe/new" class="btn btn-outline-dark mt-3">Recept hozzáadása</a>
        </div>
        <?php foreach ($recipes as $recipe) : ?>
            <div class="col-12 col-md-6 col-lg-2 mb-3 d-flex align-items-center justify-content-center">
                <div class="card recipe-card">
                    <div class="recipe-card-image" style="background-image: url(<?= isset($recipe['images']) ? "/public/assets/recipe_images/" . $recipe["images"][0]["r_imageName"] : 'https://i.imgur.com/BrotgYi.jpg'; ?>); background-size: cover; background-position: center center; min-height: 200px"></div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $recipe["recipe_name"] ?></h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="/user/recipe/<?= $recipe["recipeId"] ?>" class="btn btn-info text-light">Megtekint</a>
                        <a href="/user/recipe/update/<?= $recipe["recipeId"] ?>" class="btn btn-warning text-light">Frissit</a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $recipe["recipeId"] ?>">
                            Töröl
                        </button>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal<?= $recipe["recipeId"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <?php $current_recipe = $recipe["recipe_name"]; ?>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Figyelem!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Biztosan törlöd a &nbsp;<span class="text-danger border border-danger p-1"> <?= $current_recipe ?> </span>&nbsp; nevű receptet?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                            <a href="/user/recipe/delete/<?= $recipe["recipeId"] ?>" class="btn btn-primary">Törlés</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>