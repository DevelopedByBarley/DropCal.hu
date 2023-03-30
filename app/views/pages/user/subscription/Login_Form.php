<?php if (isset($_GET["isRegSuccess"])) : ?>
    <div class="mt-2 alert bg-info text-light" role="alert">
        Sikeres regisztráció!
    </div>
<?php endif ?>
<div class="row rounded mt-2  user-subscription-tab d-flex align-items-center justify-content-center">
    <div class="col">
        <form  action="/user/verification" method="POST">
            <div class="user-subscription-content row">
                <div class="col-xs-12  d-flex align-items-center justify-content-center">
                    <h1 class="text-center mt-5 display-6">Bejelentkezés</h1>

                </div>
                <div class="row d-flex align-items-center justify-content-center flex-column mt-5">

                    <div class="col-md-5 d-flex align-items-center justify-content-center flex-column p-3">

                        <div class="form-outline mb-4 w-100">
                            <label class="form-label" for="form1Example1">Email</label>
                            <input type="email" id="form1Example1" class="form-control " style="background: none; border: none; border-bottom: 1px solid " name="email" required value="<?= $params["registrationData"]["email"] ?? "" ?>" />
                        </div>
                        <div class="form-outline mb-4 w-100">
                            <label class="form-label" for="form1Example2">Jelszó</label>
                            <input type="password" id="form1Example2" class="form-control" style="background: none; border: none; border-bottom: 1px solid" name="password" required value="<?= $params["registrationData"]["password"] ?? "" ?>" />
                        </div>
                        <button type="submit" class="btn btn-outline-dark">Bejelentkezés</button>
                    </div>
                    <div class="col-md-5 d-flex justify-content-center align-items-center text-center">
                        <!-- Checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="on" id="form1Example3" name="remember_me" checked />
                            <label class="form-check-label" for="form1Example3"> Emlékezz rám </label>
                        </div>
                        <div class="col-md-5 m-3">
                            <!-- Simple link -->
                            <a href="#!">Elfelejtett jelszó?</a>
                        </div>
                    </div>

                </div>
            </div>

        </form>
    </div>
</div>