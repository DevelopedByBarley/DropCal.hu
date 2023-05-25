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
  </div>
  <div class="row  d-flex align-items-center justify-content-center flex-column mt-3">
    <div class="col-12 col-sm-6 ">
      <div class="search-box">
        <div class="input-group rounded">
          <input type="search" oninput="searchIngredients(event)" class="form-control rounded border" placeholder="Keresés" aria-label="Search" aria-describedby="search-addon" id="search" />
          <span class="input-group-text border-0" id="search-addon">
            <i class="bi bi-search" style="cursor: pointer" id="search-ingredient"></i>
          </span>
        </div>
      </div>
    </div>
    <ul class="col-12  col-lg-6 list-group mt-3" id="search-result-container"></ul>
  </div>
  <div class="row d-flex align-items-center justify-content-center flex-column">
    <div class="col-12 col-sm-5" id="ingredients">
      <?php if (!empty($diaryData["diary_ingredients"])) : ?>
        <ul class="list-group">
          <?php foreach ($diaryData["diary_ingredients"] as $ingredient) : ?>
            <div class="ingredient-item" data-id="<?= $ingredient["d_ingredientId"] ?>">
              <li class="list-group-item bg-dark text-light m-1 d-flex align-items-center justify-content-between w-100" style="cursor: pointer">
                <div class="name"><?= $ingredient["ingredientName"] ?></div>
                <div class="calorie"><?= $ingredient["current_calorie"] ?>Kcal</div>
              </li>
            </div>
          <?php endforeach ?>
        </ul>
      <?php endif ?>
    </div>
  </div>

  <div class="modal fade" id="staticBackdrop"  data-date="<?= $_GET["date"] ?>" data-diaryid="<?= $diaryData["userDiary"]["diaryId"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Understood</button>
        </div>
      </div>
    </div>
  </div>

</div>

<script src="public/js/diary/add_diary_ingredient.js"></script>