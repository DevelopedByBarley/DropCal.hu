<?php
$profile = $params["user"];
echo "<pre>";
var_dump($profile);
exit;
?>

<div class="row mt-5 mb-5">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <img src="/public/assets/profile_images/<?= $profile["profileImage"] ?>" class="border" alt="" style="height: 150px; width: 150px; border-radius:50%;">
    </div>
    <div class="col-12 mt-3 mb-3 text-center">
        <h1 class="display-3"> <?= $profile["userName"] ?></h1>
        <p><?= $profile["email"] ?></p>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-12 col-lg-6 border d-flex align-items-center justify-content-center flex-column bg-dark text-light" style="min-height: 300px;">
            <div>
                <p><b>Születési dátum:</b> <span><?= $profile["yearOfBirth"] ?></span></p>
                <p><b>Nem:</b> <span><?= $profile["sex"] ?></span></p>
                <p><b>Súly:</b> <span><?= $profile["currentWeight"] ?></span> kg</p>
                <p><b>Magasság:</b> <span><?= $profile["height"] ?></span> cm</p>
                <p><b>Jelszó:</b> <span>********************</span></p>
            </div>
        </div>
        <div class="col-12 col-lg-6  border d-flex align-items-center justify-content-center flex-column" style="min-height: 300px;">
            <div>
                <p><b>Aktivitás:</b> <span><?= $profile["activity"] ?></span></p>
                <p><b>Cél:</b> <span><?= $profile["goal"] ?></span></p>
                <p><b>Cukorbetegség:</b> <span><?= (int)$profile["isHaveDiabetes"] === 1 ? 'Van' : 'Nincs'  ?></span></p>
                <p><b>Diéta:</b> <span><?= $profile["diet"] ?></span></p>
                <p><b>Allergének:</b> <span><?= empty($profile["allergens"]) ? "Nincs Allergén" :  $profile["allergens"] ?></span></p>
            </div>
        </div>
        <div class="col-12 col-lg-6  border d-flex align-items-center justify-content-center flex-column" style="min-height: 300px;">
            <div>
                <p><b>BMI:</b> <span><?= $profile["BMI"] ?></span></p>
                <p><b>Státusz:</b> <span><?= $profile["stateOfBMI"] ?></span></p>
                <p><b>Egészségügyi kockázat:</b> <span><?= $profile["riskOfHealth"] ?></span></p>
            </div>
        </div>
        <div class="col-12 col-lg-6  border d-flex align-items-center justify-content-center flex-column bg-dark text-light" style="min-height: 300px;">
            <div>
                <p><b>Alapjárat kalóriaszükséglet:</b> <span><?= $profile["BMR"] ?></span> Kcal</p>
                <p><b>Alapjárat fehérjeszükséglet:</b> <span><?= $profile["protein"] ?> g</span></p>
                <p><b>Alapjárat szénhidrátszükséglet:</b> <span><?= $profile["carbohydrate"] ?></span> g</p>
                <p><b>Alapjárat zsirszükséglet:</b> <span><?= $profile["fat"] ?></span> g</p>
            </div>
        </div>
    </div>
</div>

<div class="col-xs-12 text-center mt-5">
    <a class="btn btn-danger text-light" href="/user/profile/update">
        Profil szerkesztése
    </a>    
</div>