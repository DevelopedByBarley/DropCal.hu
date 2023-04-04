<?php
$user = $params["user"];
$diaryData = isset($params["diaryData"]) ?  $params["diaryData"] :  $params["t_diaryData"];
$userFinalBMR = $user["finalBMR"] ?? $diaryData["minCalorie"];
$userProtein  = $user["protein"] ?? round(($userFinalBMR * 0.25) / 4.2, 0);
$userCarb = $user["carbohydrate"] ?? round(($userFinalBMR * 0.53) / 4.1, 0);
$userFat = $user["fat"] ?? round(($userFinalBMR * 0.22) / 9.3, 0);
$summaries = $diaryData["summaries"];
?>


<div class="p-4 shadow-4 rounded-3">
    <div class="row">
        <div class="col-xs-12"></div>
        <h1 class="text-center display-6">Üdvözöllek <?= $user["userName"] ?? '' ?></h1>
    </div>
</div>

<div class="container">
    <div class="row text-center">
        <?php if ($params["user"]) : ?>
            <form action="/diary/currentDiary" method="POST">
                <div class="date-picker">
                    <input type="date" name="currentDate" value="<?php echo isset($_POST['currentDate']) ? $_POST['currentDate'] : date('Y-m-d'); ?>" onchange="this.form.submit()">
                    <span class="icon"><i class="fa fa-calendar"></i></span>
                </div>
            </form>


        <?php endif ?>
    </div>
</div>
<div class="container bg-dark text-light rounded text-center" id="diary-ingredients-toggle">
    <div class="row ">
    </div>
    <div class="row d-flex align-items-center justify-content-center">
        <div class="col-9 col-sm-6  rounded p-5 d-flex align-items-center justify-content-center flex-column" style="box-shadow: 1px -5px 70px -52px rgba(255,255,255,1);">
            <h1 class="display-6"><?= $userFinalBMR ?> / <?= $summaries["sumOfCalorie"] ?? 0 ?></h1>
            <p>Még<b> <?= $userFinalBMR - ($summaries["sumOfCalorie"] ?? 0) ?></b> kalóriát ehetsz ma</p>
            <div class="progress" style="width: 100%; height: 2px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: <?= round(($summaries["sumOfCalorie"] / $userFinalBMR) * 100, 0) ?>%; background: hsla(199, 100%, 44%, 1);"></div>
            </div>
        </div>
        <div class="row d-flex align-items-center justify-content-center" style="min-height: 200px;">
            <div class="col-4 text-center  p-3">
                <p>Fehérje <br> <?= $summaries["sumOfProtein"] ?? 0 ?>/<?= $userProtein ?></p>
                <div class="progress" style="width: 100%; height: 2px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: <?= round(($summaries["sumOfProtein"] / $userProtein) * 100, 0) ?>%; background: hsla(199, 100%, 44%, 1);"></div>
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
</div>
<div class="container-fluid bg-dark text-light p-5" id="ingredients-toggle">
    <div class="row d-flex align-items-center justify-content-center">
        <div class="col-9 col-sm-6  rounded p-5 d-flex align-items-center justify-content-center flex-column" style="box-shadow: 1px -5px 70px -52px rgba(255,255,255,1);">
            <h1 class="display-6"><?= $userFinalBMR ?> / <?= $summaries["sumOfCalorie"] ?? 0 ?></h1>
            <p>Még<b> <?= $userFinalBMR - ($summaries["sumOfCalorie"] ?? 0) ?></b> kalóriát ehetsz ma</p>
            <div class="progress" style="width: 100%; height: 2px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: <?= round(($summaries["sumOfCalorie"] / $userFinalBMR) * 100, 0) ?>%; background: hsla(199, 100%, 44%, 1);"></div>
            </div>
        </div>
        <div class="row d-flex align-items-center justify-content-center" style="min-height: 200px;">
            <div class="col-4 text-center  p-3">
                <p>Fehérje <br> <?= $summaries["sumOfProtein"] ?? 0 ?>/<?= $userProtein ?></p>
                <div class="progress" style="width: 100%; height: 2px;" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: <?= round(($summaries["sumOfProtein"] / $userProtein) * 100, 0) ?>%; background: hsla(199, 100%, 44%, 1);"></div>
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
        <div class="row">
            <div class="col-xs-12 text-center">
                <h3> <i id="cancel-ingredients" class="bi bi-x-square"></i></h3>
            </div>
        </div>
    </div>
    <?php if (!empty($diaryData["diary_ingredients"])) : ?>
        <?php foreach ($diaryData["diary_ingredients"] as $ingredient) : ?>
            <?= $ingredient["name"] ?>
        <?php endforeach ?>
    <?php endif ?>
</div>

<style>
    .date-picker {
        position: relative;
        display: inline-block;
        width: 200px;
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .date-picker input[type="date"] {
        font-size: 16px;
        padding: 8px;
        border-radius: 5px;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .date-picker .icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: 10px;
        color: #aaa;
    }

    .fa-calendar {
        font-size: 20px;
    }

    #diary-ingredients-toggle {
        cursor: pointer;
    }

    #ingredients-toggle {
        height: 200vh;
        width: 100%;
        top: -500%;
        position: absolute;
        left: 0;
        transition: all .4s ease-in-out;
    }


    #cancel-ingredients {
        cursor: pointer;
        position: fixed;
        top:0;
        left: 20px;
        z-index: 100;
    }

    #ingredients-toggle.active {
        top: 0;
    }
</style>

<script type="text/javascript">
    const diaryToggle = document.getElementById("diary-ingredients-toggle");
    const ingredientsToggle = document.getElementById("ingredients-toggle");
    const cancelIngredients = document.getElementById("cancel-ingredients");

    diaryToggle.addEventListener('click', (event) => {
        ingredientsToggle.classList.add("active");
    })

    cancelIngredients.addEventListener("click", (event) => {
        ingredientsToggle.classList.remove("active");
    })
</script>