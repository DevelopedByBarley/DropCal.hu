<div class="row  rounded mt-2  user-subscription-tab d-flex align-items-center justify-content-center">

    <form method="POST" action="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) + 1 ?>">
        <div class="user-subscription-content mb-5">
            <h1 class="display-6 text-center mb-4">
                Válaszd ki hogy milyen étrendet preferálsz!
            </h1>
            <div class="btn-group-allergens row mt-5 d-flex align-items-center justify-content-center">
                <div class="col-5 col-lg-3 d-flex align-items-center justify-content-center">
                    <input type="radio" name="diet" id="meat" value="meat">
                    <label for="meat" class="mb-2">
                        <img src="/public/assets/icons/chicken.gif" style="height: 70px; width: 70px;" />
                        <h6>Húsimádó</h6>
                    </label>
                </div>
                <div class="col-5 col-lg-3 d-flex align-items-center justify-content-center">
                    <input type="radio" name="diet" id="vegetarian" value="vegetarian">
                    <label for="vegetarian" class="mb-2">
                        <img src="/public/assets/icons/salad.gif" style="height: 70px; width: 70px;" />
                        <h6>Vegetáriánus </h6>
                    </label>
                </div>

                <div class="col-5 col-lg-3 d-flex align-items-center justify-content-center">
                    <input type="radio" name="diet" id="vegan" value="vegan">
                    <label for="vegan" class="mb-2">
                        <img src="/public/assets/icons/vegan_2.gif" style="height: 70px; width: 70px;" />
                        <h6>Vegán </h6>
                    </label>
                </div>
                <div class="col-5 col-lg-3 d-flex align-items-center justify-content-center">
                    <input type="radio" name="diet" id="paleo" value="paleo">
                    <label for="paleo" class="mb-2">
                        <img src="/public/assets/icons/cave.gif" style="height: 70px; width: 70px;" />
                        <h6>Paleo </h6>
                    </label>
                </div>
                <div class="col-5 col-lg-3 d-flex align-items-center justify-content-center">
                    <input type="radio" name="diet" id="gluten_free" value="gluten_free">
                    <label for="gluten_free" class="mb-2">
                        <img src="/public/assets/icons/rice.gif" style="height: 70px; width: 70px;" />
                        <h6>Gluténmentes </h6>
                    </label>
                </div>
                <div class="col-5 col-lg-3 d-flex align-items-center justify-content-center">
                    <input type="radio" name="diet" id="sugar_free" value="sugar_free">
                    <label for="sugar_free" class="mb-2">
                        <img src="/public/assets/icons/candy-bag.gif" style="height: 70px; width: 70px;" />
                        <h6>Cukormentes </h6>
                    </label>
                </div>
                <div class="col-5 col-lg-3 d-flex align-items-center justify-content-center">
                    <input type="radio" name="diet" id="ketogenic" value="ketogenic">
                    <label for="ketogenic" class="mb-2">
                        <img src="/public/assets/icons/sausage.gif" style="height: 70px; width: 70px;" />
                        <h6>Ketogén </h6>
                    </label>
                </div>
            </div>
            <div class="row" style="margin-top: 80px;">

                <div class="col-xs-12 user-navigation-buttons d-flex">
                    <a class="btn btn-outline-dark m-2 p-3" href="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) - 1 ?>">Vissza</a>
                    <button type="submit" class="btn btn-outline-dark m-2 p-3">Tovább</button>

                </div>
            </div>
    </form>
</div>