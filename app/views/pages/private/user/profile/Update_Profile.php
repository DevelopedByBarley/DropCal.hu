<?php
$profile = $params["user"];
var_dump($profile);
?>

<div class="container">
    <h1 class="display-2">Fejlesztés alatt</h1>
    <form action="/user/profile/update" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col">
                <!-- Name input -->
                <div class="form-outline">
                    <label class="form-label" for="form8Example1">Név:</label>
                    <input type="text" id="form8Example1" class="form-control" name="userName" value="<?= $profile["userName"] ?>" />
                </div>
            </div>
            <div class="col">
                <!-- Email input -->
                <div class="form-outline">
                    <label class="form-label" for="form8Example2">Email</label>
                    <input type="email" id="form8Example2" class="form-control" value="<?= $profile["email"] ?>" />
                </div>
            </div>
            <div class="col">
                <!-- Email input -->
                <div class="form-outline">
                    <label class="form-label" for="form8Example2">Jelszó</label>
                    <input type="password" id="form8Example2" class="form-control" value="*********" />
                </div>
            </div>
        </div>

        <hr class="mb-5" />
        <div class="row">
            <div class="col">
                <!-- Name input -->
                <div class="form-outline">
                    <label class="form-label" for="form8Example1">Név:</label>
                    <input type="number" id="form8Example1" class="form-control" name="yearOfBirth" value="<?= $profile["yearOfBirth"] ?>" />
                </div>
            </div>
            <div class="col">
                <!-- Email input -->
                <div class="form-outline">
                    <label class="form-label" for="form8Example2">Nem</label>
                    <select name="" id="" class="form-control">
                        <option value="" disabled selected>Kiválaszt</option>
                        <option value="male">Férfi</option>
                        <option value="female">Nő</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <!-- Email input -->
                <div class="form-outline">
                    <label class="form-label" for="form8Example2">Testsúly (Kg)</label>
                    <input type="number" id="form8Example1" class="form-control" name="currentWeight" value="<?= $profile["currentWeight"] ?>" />
                </div>
            </div>
            <div class="col">
                <!-- Email input -->
                <div class="form-outline">
                    <label class="form-label" for="form8Example2">Magasság (cm)</label>
                    <input type="number" id="form8Example1" class="form-control" name="height" value="<?= $profile["height"] ?>" />
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col">
                <div class="form-outline">
                    <label class="form-label" for="form1Example2">Aktivitás</label>
                    <select class="form-select" aria-label="Default select example" name="activity" required>
                        <option value="" disabled selected>Kiválaszt</option>
                        <option value="1">Alacsony aktivitású [javarészt ülés, fekvés]</option>
                        <option value="2">Mérsékelt aktivitás [Ülőmunka vagy kevés fizikai munka]</option>
                        <option value="3">Közepes Aktivitás [Részben fizikai munka]</option>
                        <option value="4">Magas aktivitás [Állómunka vagy gyaloglás]</option>
                        <option value="5">Kiemelkedő aktivitás [Nehéz fizikai munka]</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="form-outline mb-4 w-100">
                    <label class="form-label" for="form1Example2">Cél kiválasztása</label>
                    <select class="form-select mb-4" aria-label="Default select example" name="goal" required>
                        <option value="" disabled selected>Kiválaszt</option>
                        <option value="testsúly_csökkentése">Testsúly csökkentése</option>
                        <option value="testsúly_növelése">Testsúly növelése</option>
                        <option value="testsúly_megtartása">Testsúly megtartása</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-outline mb-4 w-100">
                    <label class="form-label" for="form1Example2">Diéta</label>
                    <select class="form-select mb-4" aria-label="Default select example" name="goal" required>
                        <option value="" disabled selected>Kiválaszt</option>
                        <option value="testsúly_csökkentése">Testsúly csökkentése</option>
                        <option value="testsúly_növelése">Testsúly növelése</option>
                        <option value="testsúly_megtartása">Testsúly megtartása</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="form-outline mb-4 w-100">
                    <label class="form-label" for="form1Example2">Diéta</label>
                    <select class="form-select mb-4" aria-label="Default select example" name="goal" required>
                        <option value="" disabled selected>Kiválaszt</option>
                        <option value="testsúly_csökkentése">Testsúly csökkentése</option>
                        <option value="testsúly_növelése">Testsúly növelése</option>
                        <option value="testsúly_megtartása">Testsúly megtartása</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-outline mb-4 w-100">
                    <label class="form-label" for="form1Example2">Cukorbeteg vagyok</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="form11Example4" checked />
                        <label class="form-check-label" for="form11Example4">
                            Checked switch checkbox input
                        </label>
                    </div>
                </div>
            </div>
        </div>


    </form>
</div>