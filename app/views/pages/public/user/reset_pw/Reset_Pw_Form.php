<?php if (isset($_GET["pwVerifyProblem"])) : ?>
    <div class="mt-2 alert bg-danger text-light" role="alert">
        A Jelszavak nem egyeznek!
    </div>
<?php endif ?>

<div class="row rounded mt-2  user-subscription-tab d-flex align-items-center justify-content-center">
    <div class="col">
        <form action="/set_new_password" method="POST">
            <div class="user-subscription-content row">
                <div class="col-xs-12  d-flex align-items-center justify-content-center">
                    <h1 class="text-center mt-5 display-6">Új jelszó beállítása</h1>
                    
                </div>
                <div class="row d-flex align-items-center justify-content-center flex-column mt-5">

                    <div class="col-md-5 d-flex align-items-center justify-content-center flex-column p-3">

                        <div class="form-outline mb-4 w-100">
                            <label class="form-label" for="form1Example2">Jelszó</label>
                            <input type="password" id="form1Example2" class="form-control" style="background: none; border: none; border-bottom: 1px solid" name="password" required value="<?= $params["registrationData"]["password"] ?? "" ?>" />
                        </div>
                        <div class="form-outline mb-4 w-100">
                            <label class="form-label" for="form1Example2">Jelszó újra</label>
                            <input type="password" id="form1Example2" class="form-control" style="background: none; border: none; border-bottom: 1px solid" name="password_again" required value="<?= $params["registrationData"]["password"] ?? "" ?>" />
                        </div>
                        <input type="hidden" name="email" value="<?=$params["emailByToken"]?>">
                        <input type="hidden" name="token" value="<?=$params["token"]?>">

                        <button type="submit" class="btn btn-outline-dark"> Jelszó megváltoztatása</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>