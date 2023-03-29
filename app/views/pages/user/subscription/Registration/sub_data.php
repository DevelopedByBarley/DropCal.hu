<div class="row border rounded mt-2  user-registration-tab d-flex align-items-center justify-content-center">
    <div class="col">
        <form method="POST" action="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) + 1 ?>">
            <div class="user-registration-content row">
                <div class="col-md-6  d-flex align-items-center justify-content-center">
                    <h1 class="text-center mt-5 display-6">Vegyük fel először a bejelentkezési adatokat!</h1>

                </div>
                <div class="col-md-5 d-flex align-items-center justify-content-center flex-column p-3">

                    <div class="form-outline mb-4 w-100">
                        <label class="form-label" for="form1Example1">Felhasználónév</label>
                        <input type="text" id="form1Example0" class="form-control " style="background: none; border: none; border-bottom: 1px solid" name="userName" required value="<?= $params["registrationData"]["userName"] ?? "" ?>" />
                    </div>

                    <div class="form-outline mb-4 w-100">
                        <label class="form-label" for="form1Example1">Email</label>
                        <input type="email" id="form1Example1" class="form-control " style="background: none; border: none; border-bottom: 1px solid " name="email" required value="<?= $params["registrationData"]["email"] ?? "" ?>" />
                        <?= isset($_GET["isVerificationFail"]) ? "<p class=\"text-danger\">Email cím hitelesítése kötelező</p" : "" ?>
                        <p class="verification_state"></p>
                        <button id="verification_toggle" class="mt-2 btn btn-outline-dark">Email hitelesítése</button>
                        <span class="email_verification_container">

                            <div class="mt-3 alert alert-dark d-flex align-items-center" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                <div>
                                    Kód küldése az email címedre megtörtént!
                                </div>
                            </div>

                            <input type="number" name="verification_code" placeholder="hitelesítő kód">
                            <button class="verification_btn mb-2 btn btn-outline-info">Hitelesítés</button>
                        </span>
                    </div>

                    <div class="form-outline mb-4 w-100">
                        <label class="form-label" for="form1Example2">Jelszó</label>
                        <input type="password" id="form1Example2" class="form-control" style="background: none; border: none; border-bottom: 1px solid" name="password" required value="<?= $params["registrationData"]["password"] ?? "" ?>" />
                    </div>

                    <div class="row mb-4">
                        <div class="col d-flex justify-content-center">
                            <!-- Checkbox -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="on" name="isPolicyAccepted" id="form1Example3" required />
                                <label class="form-check-label" for="form1Example3">Az <a href="#">Adatkezelési tájékoztatót</a> elfogadom</label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 user-navigation-buttons d-flex">
                        <a class="btn btn-outline-dark m-2 p-3" href="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) - 1 ?>">Vissza</a>
                        <button type="submit" class="btn btn-outline-dark m-2 p-3">Tovább</button>
                    </div>
                </div>
        </form>
    </div>
</div>