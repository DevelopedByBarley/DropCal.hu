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
                    </div>

                    <div class="form-outline mb-4 w-100">
                        <label class="form-label" for="form1Example2">Jelszó</label>
                        <input type="password" id="form1Example2" class="form-control" style="background: none; border: none; border-bottom: 1px solid" name="password" required value="<?= $params["registrationData"]["password"] ?? "" ?>" />
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