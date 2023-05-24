<?php
$user = $params["user"];
$diaryData = isset($params["diaryData"]) ?  $params["diaryData"] :  $params["t_diaryData"];
$userFinalBMR = $user["finalBMR"] ?? $diaryData["minCalorie"];
$userProtein  = $user["protein"] ?? round(($userFinalBMR * 0.25) / 4.2, 0);
$userCarb = $user["carbohydrate"] ?? round(($userFinalBMR * 0.53) / 4.1, 0);
$userFat = $user["fat"] ?? round(($userFinalBMR * 0.22) / 9.3, 0);
$summaries = $diaryData["summaries"];
$dedicatedRecipes = $params["dedicatedRecipesForYou"];
?>


<div id="diary-component" style="min-height: 80vh">
    <div class="mt-5 shadow-4 rounded-3">
        <div class="row">
            <div class="col-xs-12"></div>
            <h1 class="text-center display-3">Szia <?= $user["userName"] ?? '' ?>!</h1>
            <p class="text-center">Csináljunk egy sikeres napot közösen!</p>
        </div>
    </div>


    <div class="container bg-light rounded text-center p-4" id="diary-ingredients-toggle">
        <div class="row "></div>
        <a href="/diary/currentDiary?date=<?= date('Y-m-d') ?>" style="text-decoration: none" class="text-dark">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-xs-12 col-sm-6  rounded mt-4  p-5 d-flex align-items-center justify-content-center flex-column" style="box-shadow: 1px -5px 70px -52px rgba(0,0,0,1);">
                    <h1 class="display-6"><?= $userFinalBMR ?> / <?= $summaries["sumOfCalorie"] ?? 0 ?></h1>
                    <p>Még<b> <?= $userFinalBMR - ($summaries["sumOfCalorie"] ?? 0) ?></b> kalóriát ehetsz ma</p>
                    <div class="progress" style="width: 100%; height: 4px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: <?= round(($summaries["sumOfCalorie"] / $userFinalBMR) * 100, 0) ?>%; background:hsla(183, 100%, 70%, 1);"></div>
                    </div>
                </div>
                <div class="row d-flex align-items-center justify-content-center" style="min-height: 200px;">
                    <div class="col-4 text-center  p-3">
                        <p>Fehérje <br> <?= $summaries["sumOfProtein"] ?? 0 ?>/<?= $userProtein ?></p>
                        <div class="progress" style="width: 100%; height: 4px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: <?= round(($summaries["sumOfProtein"] / $userProtein) * 100, 0) ?>%; background:hsla(183, 100%, 70%, 1);"></div>
                        </div>
                    </div>
                    <div class="col-4 text-center p-3">
                        <p>Szénhidrát <br> <?= $summaries["sumOfCarb"] ?? 0 ?>/<?= $userCarb ?></p>
                        <div class="progress" style="width: 100%; height: 4px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: <?= round(($summaries["sumOfCarb"] / $userCarb) * 100, 0) ?>%; background:hsla(183, 100%, 70%, 1);"></div>
                        </div>
                    </div>
                    <div class="col-4  text-center p-3">
                        <p>Zsír <br> <?= $summaries["sumOfFat"] ?? 0 ?>/<?= $userFat ?></p>
                        <div class="progress" style="width: 100%; height: 4px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: <?= round(($summaries["sumOfFat"] / $userFat) * 100, 0) ?>%; background:hsla(183, 100%, 70%, 1);"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-light" role="alert">
                <b> <i class="bi bi-hand-index" style="font-size: 2rem;"></i></b>
            </div>
        </a>
    </div>
</div>
<?php if (isset($dedicatedRecipes) && count($dedicatedRecipes) !== 0) : ?>
    <div class="row mb-5 p-3 bg-dark text-light slanted-top"></div>
<?php endif ?>
<div class="row mb-5 bg-dark text-light">
    <?php if (isset($dedicatedRecipes) && count($dedicatedRecipes) !== 0) : ?>
        <div class="col-xs-12 col-lg-6 d-flex align-items-center justify-content-center flex-column">
            <b>
                <h1 class="display-3 text-center mt-3 mb-4">Receptek személyedre szabva</h1>
            </b>
            <p class="p-3">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                Nihil saepe quis praesentium voluptates laborum unde,
                nesciunt quisquam dignissimos, exercitationem laboriosam sequi,
                quas pariatur fugit quidem dolor iusto quae. Optio, odit?
            </p>
            <a class="btn btn-outline-light btn-lg mt-4" href="#">Receptek böngészése</a>
        </div>
        <div class="col-xs-12 col-lg-6">
            <swiper-container class="mySwiper" pagination="true" effect="coverflow" grab-cursor="true" centered-slides="true" slides-per-view="auto" coverflow-effect-rotate="50" coverflow-effect-stretch="0" coverflow-effect-depth="100" coverflow-effect-modifier="1" coverflow-effect-slide-shadows="true">
                <?php foreach ($dedicatedRecipes as $recipe) : ?>
                    <swiper-slide>
                        <a href="/user/recipe/<?= $recipe["recipeId"] ?>" class="text-dark" style="text-decoration:none">
                            <div class="card recipe-card">
                                <div class="recipe-card-image" style="background-image: url(<?= isset($recipe['images']) ? "/public/assets/recipe_images/" . $recipe["images"][0]["r_imageName"] : 'https://i.imgur.com/BrotgYi.jpg'; ?>); background-size: cover; background-position: center center; min-height: 300px"></div>
                                <div class="card-body p-2 mt-3 mb-3">
                                    <h5 class="card-title"><?= $recipe["recipe_name"] ?></h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                </div>
                            </div>
                        </a>
                    </swiper-slide>
                <?php endforeach ?>
            </swiper-container>
        </div>
    <?php else : ?>
        <div class="col-xs-12 text-center d-flex align-items-center justify-content-center flex-column p-5">
            <div>
                <h1 class="display-5 text-center mt-3 mb-4">Nincs még egyetlen rád szabható recept sem</h1>
                <div class="button-group">
                    <a href="/user/recipe/new" class="btn btn-outline-light">Új recept</a>
                    <a href="#" class="btn btn-outline-light">Böngészés</a>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<div class="container bg-light">
    <div class="row p-2">
        <h1 class="display-4 text-center mb-5 mt-5">Friss cikkek</h1>
        <div class="col-xs-12 col-sm-4 d-flex align-items-center justify-content-center mt-3">
            <div class="card recipe-card">
                <div class="recipe-card-image" style="background-image: url(<?= isset($history['images']) ? "/public/assets/recipe_images/" . $recipe["images"][0]["r_imageName"] : 'https://i.imgur.com/BrotgYi.jpg'; ?>); background-size: cover; background-position: center center; min-height: 200px"></div>
                <div class="card-body">
                    <h5 class="card-title">Story</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-info text-light">Megtekint</a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 d-flex align-items-center justify-content-center mt-3">
            <div class="card recipe-card">
                <div class="recipe-card-image" style="background-image: url(<?= isset($history['images']) ? "/public/assets/history_images/" . $history['images'][0]["r_imageName"] : 'https://i.imgur.com/BrotgYi.jpg'; ?>); background-size: cover; background-position: center center; min-height: 200px"></div>
                <div class="card-body">
                    <h5 class="card-title">Story</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-info text-light">Megtekint</a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 d-flex align-items-center justify-content-center mt-3">
            <div class="card recipe-card">
                <div class="recipe-card-image" style="background-image: url(<?= isset($history['images']) ? "/public/assets/history_images/" . $history['images'][0]["r_imageName"] : 'https://i.imgur.com/BrotgYi.jpg'; ?>); background-size: cover; background-position: center center; min-height: 200px"></div>
                <div class="card-body">
                    <h5 class="card-title">Story</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-info text-light">Megtekint</a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 d-flex align-items-center justify-content-center  mt-3">
            <div class="card recipe-card">
                <div class="recipe-card-image" style="background-image: url(<?= isset($history['images']) ? "/public/assets/history_images/" . $history['images'][0]["r_imageName"] : 'https://i.imgur.com/BrotgYi.jpg'; ?>); background-size: cover; background-position: center center; min-height: 200px"></div>
                <div class="card-body">
                    <h5 class="card-title">Story</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-info text-light">Megtekint</a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 d-flex align-items-center justify-content-center  mt-3">
            <div class="card recipe-card">
                <div class="recipe-card-image" style="background-image: url(<?= isset($history['images']) ? "/public/assets/history_images/" . $history['images'][0]["r_imageName"] : 'https://i.imgur.com/BrotgYi.jpg'; ?>); background-size: cover; background-position: center center; min-height: 200px"></div>
                <div class="card-body">
                    <h5 class="card-title">Story</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-info text-light">Megtekint</a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 d-flex align-items-center justify-content-center  mt-3">
            <div class="card recipe-card">
                <div class="recipe-card-image" style="background-image: url(<?= isset($history['images']) ? "/public/assets/history_images/" . $history['images'][0]["r_imageName"] : 'https://i.imgur.com/BrotgYi.jpg'; ?>); background-size: cover; background-position: center center; min-height: 200px"></div>
                <div class="card-body">
                    <h5 class="card-title">Story</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-info text-light">Megtekint</a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 text-center mt-5 mb-3">
            <a class="btn btn-outline-dark" href="#">További hírek</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-element-bundle.min.js"></script>



    <style>
        .slanted-top {
            transform: skewY(2deg);
            min-height: 80px;
            position: relative;
            top: 80px;
        }

        swiper-container {
            width: 100%;
            padding-top: 50px;
            padding-bottom: 50px;

        }

        swiper-slide {
            background-position: center;
            background-size: cover;
            width: 300px;
            min-height: 300px;
        }

        swiper-slide img {
            display: block;
            width: 100%;
        }
    </style>