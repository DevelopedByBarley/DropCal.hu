<?php
$user = $params["user"];
$diaryData = isset($params["diaryData"]) ?  $params["diaryData"] :  $params["t_diaryData"];
$userFinalBMR = $user["finalBMR"] ?? $diaryData["minCalorie"];
$userProtein  = $user["protein"] ?? round(($userFinalBMR * 0.25) / 4.2, 0);
$userCarb = $user["carbohydrate"] ?? round(($userFinalBMR * 0.53) / 4.1, 0);
$userFat = $user["fat"] ?? round(($userFinalBMR * 0.22) / 9.3, 0);
$summaries = $diaryData["summaries"];
?>


<div class="mt-5 shadow-4 rounded-3">
    <div class="row">
        <div class="col-xs-12"></div>
        <h1 class="text-center display-6">Szia <?= $user["userName"] ?? '' ?>!</h1>
        <p class="text-center">Csináljunk egy sikeres napot közösen!</p>
    </div>
</div>


<div class="container bg-light rounded text-center" id="diary-ingredients-toggle">
    <div class="row ">
    </div>
    <a href="/diary/currentDiary?date=<?=  date('Y-m-d')?>" style="text-decoration: none" class="text-dark">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-xs-12 col-sm-6  rounded mt-4  p-5 d-flex align-items-center justify-content-center flex-column" style="box-shadow: 1px -5px 70px -52px rgba(0,0,0,1);">
                <h1 class="display-6"><?= $userFinalBMR ?> / <?= $summaries["sumOfCalorie"] ?? 0 ?></h1>
                <p>Még<b> <?= $userFinalBMR - ($summaries["sumOfCalorie"] ?? 0) ?></b> kalóriát ehetsz ma</p>
                <div class="progress" style="width: 100%; height: 2px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: <?= round(($summaries["sumOfCalorie"] / $userFinalBMR) * 100, 0) ?>%; background: hsla(183, 100%, 69%, 1);"></div>
                </div>
            </div>
            <div class="row d-flex align-items-center justify-content-center" style="min-height: 200px;">
                <div class="col-4 text-center  p-3">
                    <p>Fehérje <br> <?= $summaries["sumOfProtein"] ?? 0 ?>/<?= $userProtein ?></p>
                    <div class="progress" style="width: 100%; height: 2px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: <?= round(($summaries["sumOfProtein"] / $userProtein) * 100, 0) ?>%; background: hsla(183, 100%, 69%, 1);"></div>
                    </div>
                </div>
                <div class="col-4 text-center p-3">
                    <p>Szénhidrát <br> <?= $summaries["sumOfCarb"] ?? 0 ?>/<?= $userCarb ?></p>
                    <div class="progress" style="width: 100%; height: 2px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: <?= round(($summaries["sumOfCarb"] / $userCarb) * 100, 0) ?>%; background: hsla(199, 100%, 44%, 1);"></div>
                    </div>
                </div>
                <div class="col-4  text-center p-3">
                    <p>Zsír <br> <?= $summaries["sumOfFat"] ?? 0 ?>/<?= $userFat ?></p>
                    <div class="progress" style="width: 100%; height: 2px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: <?= round(($summaries["sumOfFat"] / $userFat) * 100, 0) ?>%; background: hsla(199, 100%, 44%, 1);"></div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>