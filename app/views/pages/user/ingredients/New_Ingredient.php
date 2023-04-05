<div class="container-fluid add-ingredient-container bg-light d-flex align-items-center justify-content-center">
    <div class="add-ingredient-container-cancel">
        <h5>
            <i class="bi bi-x-square"></i>
        </h5>
    </div>
    <div class="row rounded mt-2 d-flex align-items-center justify-content-center">
        <div class="col">
            <form onsubmit="sendIngredient(event)">
                <div class="user-subscription-content row mb-5">
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <h1 class="text-center mt-5 display-6">Étel hozzáadása</h1>

                    </div>
                    <div class="col-lg-5 mt-4 d-flex align-items-center justify-content-center flex-column p-3">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-2">
                                <div class="form-outline mb-4 w-100">
                                    <label class="form-label" for="form1Example1">Étel neve</label>
                                    <input type="text" min="1" id="form1Example0" class="form-control" style="background: none; border: none; border-bottom: 1px solid" name="ingredientName" placeholder="Étel neve"  />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6  mb-2">
                                <div class="form-outline mb-4 w-100">
                                    <label class="form-label" for="form1Example2">Kategória</label>
                                    <select class="form-select" aria-label="Default select example" name="ingredientCategorie" >
                                        <option value="" disabled selected>Választ</option>
                                        <?php foreach ($params["ingredientCategories"] as $categorie) : ?>
                                            <option value="<?= $categorie ?>"> <?= $categorie ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6  mb-2">
                                <div class="form-outline mb-4 w-100">
                                    <label class="form-label" for="form1Example2">Egység</label>
                                    <select class="form-select" aria-label="Default select example" name="unit" >
                                        <option value="" disabled selected>Választ</option>
                                        <?php foreach ($params["ingredientCategories"] as $categorie) : ?>
                                            <option value="<?= $categorie ?>"> <?= $categorie ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-5  mb-2">
                                <div class="form-outline mb-4 w-100">
                                    <label class="form-label" for="form1Example1">Kalória</label>
                                    <input type="text" value="<?= $params["registrationData"]["currentWeight"] ?? "" ?>" id="form1Example1" class="form-control" placeholder="Kalória" style="background: none; border: none; border-bottom: 1px solid " name="calorie"  />
                                </div>
                            </div>
                            <div class="col-12 col-sm-3  mb-2">
                                <div class="form-outline mb-4 w-100">
                                    <label class="form-label" for="form1Example1">Fehérje</label>
                                    <input type="number" min="1" value="<?= $params["registrationData"]["height"] ?? "" ?>" id="form1Example1" class="form-control" placeholder="Fehérje" style="background: none; border: none; border-bottom: 1px solid " name="protein"  />
                                </div>
                            </div>
                            <div class="col-12 col-sm-3  mb-2">
                                <div class="form-outline mb-4 w-100">
                                    <label class="form-label" for="form1Example1">Szénhidrát</label>
                                    <input type="number" min="1" value="<?= $params["registrationData"]["height"] ?? "" ?>" id="form1Example1" class="form-control" placeholder="Szénhidrát" style="background: none; border: none; border-bottom: 1px solid " name="carb"  />
                                </div>
                            </div>
                            <div class="col-12 col-sm-3  mb-2">
                                <div class="form-outline mb-4 w-100">
                                    <label class="form-label" for="form1Example1">Zsír</label>
                                    <input type="number" min="1" value="<?= $params["registrationData"]["height"] ?? "" ?>" id="form1Example1" class="form-control" placeholder="Zsír" style="background: none; border: none; border-bottom: 1px solid " name="fat"  />
                                </div>
                            </div>
                            <div class="col-12 col-sm-5 mb-2">
                                <div class="form-outline mb-4 w-100">
                                    <label class="form-label" for="form1Example2">Leggyakoribb egység súlyának megadása</label>
                                    <select class="form-select" aria-label="Default select example" name="c_unit" >
                                        <option value="" disabled selected>Egység</option>
                                        <?php foreach ($params["ingredientCategories"] as $categorie) : ?>
                                            <option value="<?= $categorie ?>"> <?= $categorie ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-5  mb-2">
                                <div class="form-outline mb-4 w-100">
                                    <label class="form-label" for="form1Example1">Egység súlya(g)</label>
                                    <input type="number" value="<?= $params["registrationData"]["currentWeight"] ?? "" ?>" id="form1Example1" class="form-control" placeholder="Egység súlya" style="background: none; border: none; border-bottom: 1px solid " name="c_weight"  />
                                </div>
                            </div>
                            <div class="col-12 col-sm-5  mb-2">
                                <div class="form-outline mb-4 w-100">
                                    <label class="form-label" for="form1Example1">Glikémiás index</label>
                                    <input type="number" id="form1Example1" class="form-control" placeholder="Glikémiás Index" style="background: none; border: none; border-bottom: 1px solid " name="glychemicIndex" />
                                </div>
                            </div>
                            <div class="col-12 allergens">
                                <p>Allergének:</p>
                                <div class="allergen-container">
                                </div>
                            </div>
                            <div class="col-12 d-flex flex-column justify-content-center">
                                <!-- Checkbox -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="on" id="form1Example3" name="isRecommended" />
                                    <label class="form-check-label" for="form1Example3"> Ajánlás a publikus csoportba </label>
                                </div>
                                <input type="hidden" name="allergens" id="allergen-input" value=>
                            </div>
                        </div>
                        <button type="submit" class="mt-3 btn btn-outline-dark" id="send-ingredient">Hozzáad</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>