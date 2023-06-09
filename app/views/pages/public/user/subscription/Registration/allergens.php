<div class="container">
    <div class="row  rounded mt-2  user-subscription-tab">
        <div class="col mt-5">
            <form method="POST" action="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) + 1 ?>">
                <div class="user-subscription-content mb-5">
                    <h1 class="display-6 text-center mb-4">
                        Válaszd ki ha van valamilyen allergiád!
                    </h1>
                    <div class="btn-group-inputs row mt-5">
                        <div class="col-6 col-sm-4 col-lg-2 d-flex align-items-center justify-content-center">
                            <input type="checkbox" name="allergens[]" id="gluten" value="Glutén">
                            <label for="gluten" class="mb-2">
                                <img src="/public/assets/icons/gluten.png" style="height: 50px; width: 50px;" />
                                <span>Glutén</span>
                            </label>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2 d-flex align-items-center justify-content-center">
                            <input type="checkbox" name="allergens[]" id="milk" value="Tej">
                            <label for="milk" class="mb-2">
                                <img src="/public/assets/icons/milk.png" style="height: 50px; width: 50px;" />
                                <span>Tej</span>
                            </label>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2 d-flex align-items-center justify-content-center">
                            <input type="checkbox" name="allergens[]" id="celery" value="Zeller">
                            <label for="celery" class="mb-2">
                                <img src="/public/assets/icons/celery.png" style="height: 50px; width: 50px;" />
                                <span>Zeller</span>
                            </label>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2 d-flex align-items-center justify-content-center">
                            <input type="checkbox" name="allergens[]" id="peanut" value="Földimogyoró">
                            <label for="peanut" class="mb-2">
                                <img src="/public/assets/icons/peanut.png" style="height: 50px; width: 50px;" />
                                <span>Mogyoró</span>
                            </label>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2 d-flex align-items-center justify-content-center">
                            <input type="checkbox" name="allergens[]" id="crustaceans" value="Rákfélék">
                            <label for="crustaceans" class="mb-2">
                                <img src="/public/assets/icons/crustaceans.png" style="height: 50px; width: 50px;" />
                                <span>Rákfélék</span>
                            </label>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2 d-flex align-items-center justify-content-center">
                            <input type="checkbox" name="allergens[]" id="mushroom" value="Halak">
                            <label for="mushroom" class="mb-2">
                                <img src="/public/assets/icons/mushroom.png" style="height: 50px; width: 50px;" />
                                <span>Halak</span>
                            </label>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2 d-flex align-items-center justify-content-center">
                            <input type="checkbox" name="allergens[]" id="mustard" value="Mustár">
                            <label for="mustard" class="mb-2">
                                <img src="/public/assets/icons/mustard.png" style="height: 50px; width: 50px;" />
                                <span>Mustár</span>
                            </label>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2 d-flex align-items-center justify-content-center">
                            <input type="checkbox" name="allergens[]" id="soybean" value="Szójabab">
                            <label for="soybean" class="mb-2">
                                <img src="/public/assets/icons/soybean.png" style="height: 50px; width: 50px;" />
                                <span>Szójabab</span>
                            </label>
                        </div>
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
    </div>
</div>