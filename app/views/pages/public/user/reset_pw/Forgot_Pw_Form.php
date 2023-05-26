
<div class="row rounded mt-2  user-subscription-tab d-flex align-items-center justify-content-center">
    <div class="col">
        <form action="/pw_request" method="POST">
            <div class="user-subscription-content row">
                <div class="col-xs-12  d-flex align-items-center justify-content-center">
                    <h1 class="text-center mt-5 display-6">Elfelejtett jelszó</h1>
                    
                </div>
                <div class="row d-flex align-items-center justify-content-center flex-column mt-5">

                    <div class="col-md-5 d-flex align-items-center justify-content-center flex-column p-3">

                        <div class="form-outline mb-4 w-100">
                            <label class="form-label" for="form1Example1">Email</label>
                            <input type="email" id="form1Example1" class="form-control " style="background: none; border: none; border-bottom: 1px solid " name="email" required value="<?= $params["registrationData"]["email"] ?? "" ?>" />
                        </div>

                        <button type="submit" class="btn btn-outline-dark"> Ellenörzés</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>