

<div class="row  rounded mt-2  user-registration-tab d-flex align-items-center justify-content-center">
    <div class="col">
        <form method="POST" action="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) + 1 ?>" enctype="multipart/form-data">
            <div class="user-registration-content row mb-5">
                <div class="col-lg-6 d-flex align-items-center justify-content-center">
                    <h1 class="text-center mt-5 display-6">Tegyük egyedivé a személyes fiókodat!</h1>

                </div>
                <div class="col-lg-5 mt-4 d-flex align-items-center justify-content-center flex-column p-3">
                    <div class="row">
                        <div class="col-12 col-sm-4 mb-2">
                            <div class="form-outline mb-4 w-100">
                                <label class="form-label" for="form1Example1">Születési év</label>
                                <input type="number" min="1" id="form1Example0" class="form-control" style="background: none; border: none; border-bottom: 1px solid" name="yearOfBirth" value="<?= $params["registrationData"]["yearOfBirth"] ?? "1950" ?>" required />
                            </div>
                        </div>
                        <div class="col-12 col-sm-4  mb-2">
                            <div class="form-outline mb-4 w-100">
                                <label class="form-label" for="form1Example2">Nem</label>
                                <select class="form-select" aria-label="Default select example" name="sex" required>
                                    <option value="" disabled selected>Kiválaszt</option>
                                    <option value="férfi">Férfi</option>
                                    <option value="nő">Nő</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4  mb-2">
                            <div class="form-outline mb-4 w-100">
                                <label class="form-label" for="form1Example1">Jelenlegi testsúly</label>
                                <input type="text" value="<?= $params["registrationData"]["currentWeight"] ?? "" ?>" id="form1Example1" class="form-control" placeholder="testsúly/kg" style="background: none; border: none; border-bottom: 1px solid " name="currentWeight" required />
                            </div>
                        </div>
                        <div class="col-12 col-sm-3  mb-2">
                            <div class="form-outline mb-4 w-100">
                                <label class="form-label" for="form1Example1">Magasság</label>
                                <input type="number" min="1" value="<?= $params["registrationData"]["height"] ?? "" ?>" id="form1Example1" class="form-control" placeholder="Magasság" style="background: none; border: none; border-bottom: 1px solid " name="height" required />
                            </div>
                        </div>
                        <div class="col-12 col-sm-9  mb-2">
                            <div class="form-outline mb-4 w-100">
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
                        <div class="col-12 d-flex align-items-center justify-content-center flex-column">
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
                </div>
            </div>
            <div class="row mt-5">

                <div class="col-xs-12 user-navigation-buttons d-flex">
                    <a class="btn btn-outline-dark m-2 p-3" href="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) - 1 ?>">Vissza</a>
                    <button type="submit" class="btn btn-outline-dark m-2 p-3">Tovább</button>

                </div>
            </div>
        </form>
    </div>
</div>