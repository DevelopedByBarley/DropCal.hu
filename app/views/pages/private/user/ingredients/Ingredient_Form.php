<?php
$ingredient = $params["ingredient"];
$ingredientUnit = $ingredient["unit_quantity"] . "" . $ingredient["unit"];
?>

<div class="container-fluid add-ingredient-container bg-light d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row rounded mt-2 d-flex align-items-center justify-content-center">
            <div class="col">
                <form onsubmit="sendIngredient(event)" data-exist="<?= isset($ingredient) ? $ingredient : false ?>" data-id="<?= isset($ingredient) ? $ingredient["ingredientId"] : null ?>">
                    <div class="user-subscription-content row mb-5">
                        <div class="col-xs-12 d-flex align-items-center justify-content-center flex-column">
                            <h1 class="text-center mt-5 display-6"><?= isset($ingredient) ? "Étel frissitése" : "Étel hozzáadása" ?></h1>
                            <div class="alert bg-dark text-light p-5 m-3" role="alert">
                                <h4 class="alert-heading display-6">Figyelem!</h4>
                                <hr>
                                <p>
                                    Ha az ételed ajánlod a közös csoportba akkor a Glikémiás
                                    index és allergének megadása kötelező! Hamis adatok
                                    vagy már meglévő ételek újra feltöltése esetén azonnali és végleges kizárás jár!
                                </p>
                            </div>
                        </div>
                        <div class="col-xs-12 mt-4 d-flex align-items-center justify-content-center flex-column p-3">
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="form-outline mb-4 w-100">
                                        <label class="form-label" for="form1Example1">Étel neve</label>
                                        <input required type="text" min="1" id="form1Example0" class="form-control" style="background: none; border: none; border-bottom: 1px solid" name="ingredientName" placeholder="Étel neve" value="<?= isset($ingredient["ingredientName"]) ? $ingredient["ingredientName"] : '' ?>" />
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6  mb-2">
                                    <div class="form-outline mb-4 w-100">
                                        <label class="form-label" for="form1Example2">Kategória</label>
                                        <select class="form-select" aria-label="Default select example" id="ingredientCategorie" name="ingredientCategorie" required>
                                            <option value="" disabled selected>Választ</option>$
                                            <?php foreach ($params["ingredientCategories"] as $categorie) : ?>
                                                <?php if (isset($ingredient)) : ?>
                                                    <option value="<?= $categorie ?>" <?php echo $ingredient["ingredientCategorie"] === $categorie ? "selected" : "" ?>> <?= $categorie ?></option>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6  mb-2">
                                    <div class="form-outline mb-4 w-100">
                                        <label class="form-label" for="form1Example2">Egység</label>
                                        <select class="form-select" aria-label="Default select example" id="unit" name="unit" required>
                                            <option value="" disabled selected>Választ</option>
                                            <?php foreach ($params["units"] as $unit) : ?>
                                                <?php if (isset($ingredient)) : ?>
                                                    <option value="<?= $unit ?>" <?php echo $ingredientUnit === $unit ? "selected" : "" ?>> <?= $unit ?></option>
                                                <?php endif ?>
                                            <?php endforeach ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-5  mb-2">
                                    <div class="form-outline mb-4 w-100">
                                        <label class="form-label" for="form1Example1">Kalória</label>
                                        <input required type="text" class="form-control" placeholder="Kalória" style="background: none; border: none; border-bottom: 1px solid" id="calorie" name="calorie" value="<?= isset($ingredient) ? $ingredient["calorie"] : '' ?>" />
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3  mb-2">
                                    <div class="form-outline mb-4 w-100">
                                        <label class="form-label" for="form1Example1">Fehérje</label>
                                        <input required type="text" id="protein" class="form-control" placeholder="Fehérje" style="background: none; border: none; border-bottom: 1px solid " name="protein" value="<?= isset($ingredient) ? $ingredient["protein"] : '' ?>" />
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3  mb-2">
                                    <div class="form-outline mb-4 w-100">
                                        <label class="form-label" for="form1Example1">Szénhidrát</label>
                                        <input required type="text" id="carb" class="form-control" placeholder="Szénhidrát" style="background: none; border: none; border-bottom: 1px solid " name="carb" value="<?= isset($ingredient) ? $ingredient["carb"] : '' ?>" />
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3  mb-2">
                                    <div class="form-outline mb-4 w-100">
                                        <label class="form-label" for="form1Example1">Zsír</label>
                                        <input required type="text" id="fat" class="form-control" placeholder="Zsír" style="background: none; border: none; border-bottom: 1px solid " name="fat" value="<?= isset($ingredient) ? $ingredient["fat"] : '' ?>" />
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label class="form-label" for="form1Example1">Gyakori egység</label>
                                        <select class="form-select" id="common_unit" aria-label="Default select example" name="common_unit">
                                            <option value="" disabled selected>Választ</option>
                                            <?php foreach ($params["common_units"] as $unit) : ?>
                                                <?php if (isset($ingredient)) : ?>
                                                    <option value="<?= $unit ?>" <?php echo $unit === $ingredient["common_unit"] ? "selected" : "" ?>> <?= $unit ?></option>
                                                <?php endif ?>

                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="form1Example1">Gyakor egység súlya (g / ml)</label>
                                        <input required type="number" min="1" id="common_unit_quantity" class="form-control" placeholder="Gyakori egység súlya" style="background: none; border: none; border-bottom: 1px solid" name="common_unit_quantity" value="<?= isset($ingredient) ? $ingredient["common_unit_quantity"] : '' ?>" />
                                    </div>
                                </div>
                                <div class="col-12 col-sm-5  mb-2">
                                    <div class="form-outline mb-4 w-100">
                                        <label class="form-label" for="form1Example1">Glikémiás index</label>
                                        <input type="number" id="glychemicIndex" class="form-control" placeholder="Glikémiás Index" style="background: none; border: none; border-bottom: 1px solid " name="glychemicIndex" value="<?= isset($ingredient) ? $ingredient["glycemicIndex"] : '' ?>" />
                                    </div>
                                    <p class="text-danger" id="glycemic-alert"></p>
                                </div>
                                <div class="col-12 allergens">
                                    <p>Allergének:</p>
                                    <button type="button" class="btn btn-outline-dark" id="no-allergen">Nincs Allergén!</button>
                                    <div class="allergen-container">
                                    </div>
                                    <p class="text-danger" id="allergens-alert"></p>
                                </div>
                                <input type="hidden" name="allergens" id="allergen-input" required data-checkbox-id="isRecommended">


                                <!-- Át kell adnunk valahogy a javascriptnek az allergének adatait hogy update-re készek legyenek!-->
                                <?php if (isset($ingredient["allergens"])) : ?>
                                    <?php foreach ($ingredient["allergens"] as $allergen) : ?>
                                        <div class="allergen_update_value" data-name="<?= $allergen["i_allergenName"] ?>" data-id="<?= $allergen["i_allergenNumber"] ?>">

                                        </div>
                                    <?php endforeach ?>
                                <?php endif ?>

                                <div class="col-12 d-flex flex-column justify-content-center mt-4">
                                    <!-- Checkbox -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" <?= (isset($ingredient) && (int)$ingredient["isRecommended"] === 1) ? "checked" : "" ?> value="<?= $ingredient["isRecommended"] === 1 ? 1 : "" ?>" id="isRecommended" name="isRecommended" />
                                        <label class="form-check-label" for="form1Example3"> Ajánlás a publikus csoportba </label>
                                    </div>
                                </div>
                                <div class="col-12 text-center d-flex align-items-center justify-content-center">
                                    <button type="submit" class="mt-3 btn btn-outline-dark"><?= isset($ingredient) ? "Frissit" : "Hozzáad"  ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="public/js/ingredient/ingredient_form.js"></script>