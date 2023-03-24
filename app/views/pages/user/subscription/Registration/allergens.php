<div class="row user-registration-tab">
    <div class="col mt-5">
        <form method="POST" action="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) + 1 ?>">
            <div class="user-registration-content">
                <h1 class="display-6 text-center mb-4">
                    Válaszd ki ha van valamilyen allergiád!
                </h1>
                <div class="btn-group-allergens row mt-5">
                    <div class="col-4 col-lg-2 d-flex align-items-center justify-content-center">
                        <input type="checkbox" name="allergens[]" id="gluten" value="glutén">
                        <label for="gluten" class="mb-2">
                            <img src="/public/assets/icons/gluten.png" style="height: 50px; width: 50px;" />
                            <span>Glutén</span>
                        </label>
                    </div>
                    <div class="col-4 col-lg-2 d-flex align-items-center justify-content-center">
                        <input type="checkbox" name="allergens[]" id="milk" value="tej">
                        <label for="milk" class="mb-2">
                            <img src="/public/assets/icons/milk.png" style="height: 50px; width: 50px;" />
                            <span>Tej</span>
                        </label>
                    </div>
                    <div class="col-4 col-lg-2 d-flex align-items-center justify-content-center">
                        <input type="checkbox" name="allergens[]" id="celery" value="zeller">
                        <label for="celery" class="mb-2">
                            <img src="/public/assets/icons/celery.png" style="height: 50px; width: 50px;" />
                            <span>Zeller</span>
                        </label>
                    </div>
                    <div class="col-4 col-lg-2 d-flex align-items-center justify-content-center">
                        <input type="checkbox" name="allergens[]" id="peanut" value="mogyoró">
                        <label for="peanut" class="mb-2">
                            <img src="/public/assets/icons/peanut.png" style="height: 50px; width: 50px;" />
                            <span>Mogyoró</span>
                        </label>
                    </div>
                    <div class="col-4 col-lg-2 d-flex align-items-center justify-content-center">
                        <input type="checkbox" name="allergens[]" id="crustaceans" value="rákfélék">
                        <label for="crustaceans" class="mb-2">
                            <img src="/public/assets/icons/crustaceans.png" style="height: 50px; width: 50px;" />
                            <span>Rákfélék</span>
                        </label>
                    </div>
                    <div class="col-4 col-lg-2 d-flex align-items-center justify-content-center">
                        <input type="checkbox" name="allergens[]" id="mushroom" value="gombafélék">
                        <label for="mushroom" class="mb-2">
                            <img src="/public/assets/icons/mushroom.png" style="height: 50px; width: 50px;" />
                            <span>Gombafélék</span>
                        </label>
                    </div>
                    <div class="col-4 col-lg-2 d-flex align-items-center justify-content-center">
                        <input type="checkbox" name="allergens[]" id="mustard" value="mustár">
                        <label for="mustard" class="mb-2">
                            <img src="/public/assets/icons/mustard.png" style="height: 50px; width: 50px;" />
                            <span>Mustár</span>
                        </label>
                    </div>
                    <div class="col-4 col-lg-2 d-flex align-items-center justify-content-center">
                        <input type="checkbox" name="allergens[]" id="soybean" value="szójabab">
                        <label for="soybean" class="mb-2">
                            <img src="/public/assets/icons/soybean.png" style="height: 50px; width: 50px;" />
                            <span>Szójabab</span>
                        </label>
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