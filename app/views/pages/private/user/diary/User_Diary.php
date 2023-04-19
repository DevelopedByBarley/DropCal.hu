<?php
$user = $params["user"];
$diaryData = isset($params["diaryData"]) ?  $params["diaryData"] :  $params["t_diaryData"];
$diaryDate = isset($diaryData["userDiary"]["diaryDate"]) ?  date('Y-m-d', $diaryData["userDiary"]["diaryDate"]) : $_POST['currentDate'];
$userFinalBMR = $user["finalBMR"] ?? $diaryData["minCalorie"];
$userProtein  = $user["protein"] ?? round(($userFinalBMR * 0.25) / 4.2, 0);
$userCarb = $user["carbohydrate"] ?? round(($userFinalBMR * 0.53) / 4.1, 0);
$userFat = $user["fat"] ?? round(($userFinalBMR * 0.22) / 9.3, 0);
$summaries = $diaryData["summaries"];

?>

<div class="diary">

  <div class="container">
    <div class="row text-center">
      <h1 class="display-6 mt-5">
        Napló
      </h1>
    </div>

    <div class="row text-center">
      <?php if ($params["user"]) : ?>
        <form action="/diary/currentDiary" method="GET">
          <div class="date-picker">
            <input type="date" name="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : (isset($diaryDate) ? $diaryDate : date('Y-m-d')); ?>" onchange="this.form.submit()"> <span class="icon"><i class="fa fa-calendar"></i></span>
          </div>
        </form>


      <?php endif ?>
    </div>
  </div>
  <div class="container bg-light text-dark"> <!-- addActive -->
    <div class="row d-flex align-items-center justify-content-center">
      <div class="col-11 col-sm-6  rounded p-5 d-flex align-items-center justify-content-center flex-column" style="box-shadow: 1px -5px 70px -52px hsla(183, 100%, 0%, 1);">
        <h1 class="display-6"><?= $userFinalBMR ?> / <?= $summaries["sumOfCalorie"] ?? 0 ?></h1>
        <p>Még<b> <?= $userFinalBMR - ($summaries["sumOfCalorie"] ?? 0) ?></b> kalóriát ehetsz ma</p>
        <div class="progress" style="width: 100%; height: 4px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
          <div class="progress-bar" style="width: <?= round(($summaries["sumOfCalorie"] / $userFinalBMR) * 100, 0) ?>%; background:hsla(183, 100%, 44%, 1);"></div>
        </div>
      </div>
      <div class="row d-flex align-items-center justify-content-center" style="min-height: 200px;">
        <div class="col-4 text-center  p-3">
          <p>Fehérje <br> <?= $summaries["sumOfProtein"] ?? 0 ?>/<?= $userProtein ?></p>
          <div class="progress" style="width: 100%; height: 4px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: <?= round(($summaries["sumOfProtein"] / $userProtein) * 100, 0) ?>%; background:hsla(183, 100%, 44%, 1);"></div>
          </div>
        </div>
        <div class="col-4 text-center p-3">
          <p>Szénhidrát <br> <?= $summaries["sumOfCarb"] ?? 0 ?>/<?= $userCarb ?></p>
          <div class="progress" style="width: 100%; height: 4px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: <?= round(($summaries["sumOfCarb"] / $userCarb) * 100, 0) ?>%; background:hsla(183, 100%, 44%, 1);"></div>
          </div>
        </div>
        <div class="col-4  text-center p-3">
          <p>Zsír <br> <?= $summaries["sumOfFat"] ?? 0 ?>/<?= $userFat ?></p>
          <div class="progress" style="width: 100%; height: 4px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: <?= round(($summaries["sumOfFat"] / $userFat) * 100, 0) ?>%; background:hsla(183, 100%, 44%, 1);"></div>
          </div>
        </div>
      </div>
      <div class="row  d-flex align-items-center justify-content-center flex-column">
        <div class="col-12 col-sm-3 ">
          <div class="search-box">
            <div class="input-group rounded">
              <input type="search" oninput="searchIngredients(event)" class="form-control rounded border" placeholder="Keresés" aria-label="Search" aria-describedby="search-addon" />
              <span class="input-group-text border-0" id="search-addon">
                <i class="bi bi-search" style="cursor: pointer" id="search-ingredient"></i>
              </span>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-5" id="search-result-container"></div>
      </div>
    </div>
    <div class="row d-flex align-items-center justify-content-center flex-column">
      <div class="col-12 col-sm-5" id="ingredients">
        <?php if (!empty($diaryData["diary_ingredients"])) : ?>
          <ul class="list-group">
            <?php foreach ($diaryData["diary_ingredients"] as $ingredient) : ?>
              <div class="ingredient-item">
                <?php if ((int)$ingredient["partOfTheDay"] === 1) : ?>
                  <li class="list-group-item bg-info text-light m-1"><?= $ingredient["name"] ?> <?= $ingredient["calorie"]?></li>

                <?php elseif ((int)$ingredient["partOfTheDay"] === 2) : ?>
                  <li class="list-group-item bg-success text-light m-1"><?= $ingredient["name"] ?></li>
                <?php else : ?>
                  <li class="list-group-item bg-warning text-light m-1"><?= $ingredient["name"] ?>  <?= $ingredient["calorie"]?>Kcal</li>
                <?php endif ?>
              </div>
            <?php endforeach ?>
          </ul>
        <?php endif ?>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-3 bg-dark d-flex flex-column align-items-center justify-content-center container-fluid text-light p-5" id="single-ingredient-form" data-date="<?= $_GET["date"] ?>" data-diaryid="<?= $diaryData["userDiary"]["diaryId"] ?>"></div>
</div>