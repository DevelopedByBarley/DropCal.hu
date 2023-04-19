<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if (isset($params["isSuccess"])) : ?>
                <div class="alert bg-dark text-light text-center" role="alert">
                    Étel hozzáadása sikeres volt! <br> ha a közös csoportba ajánlottad adminjaink hamarosan bevizsgálják!
                </div>
            <?php endif ?>
            <div class="table-responsive mt-5">

                <table class="table  mt-5">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Név</th>
                            <th scope="col">Kategória</th>
                            <th scope="col">Mennyiség</th>
                            <th scope="col">Kalória</th>
                            <th scope="col">Fehérje</th>
                            <th scope="col">Szénhidrát</th>
                            <th scope="col">Zsír</th>
                            <th scope="col">Glikémiás index</th>
                            <th scope="col">Allergének</th>
                            <th scope="col">Publikálásra ajánlva</th>
                            <th scope="col">Elfogadva</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($params["ingredients"] as $ingredient) : ?>
                            <tr>
                                <th scope="row"><?= $ingredient["ingredientId"] ?></th>
                                <td><?= $ingredient["ingredientName"] ?></td>
                                <td><?= $ingredient["ingredientCategorie"] ?></td>
                                <td><?= $ingredient["unit"] ?></td>
                                <td><?= $ingredient["calorie"] ?>Kcal</td>
                                <td><?= $ingredient["protein"] ?>g</td>
                                <td><?= $ingredient["carb"] ?>g</td>
                                <td><?= $ingredient["fat"] ?>g</td>
                                <td><?= $ingredient["glycemicIndex"] ?></td>
                                <td>
                                    <?php foreach ($ingredient["allergens"] as $allergen) : ?>
                                        <?= (int)$allergen["i_allergenNumber"] ?>,
                                    <?php endforeach ?>
                                </td>
                                <td><?= (int)$ingredient["isRecommended"] === 0 ? "<i class=\"bi bi-x-circle-fill text-danger\"></i>" : "<i class=\"bi bi-check-circle-fill text-success\"></i>" ?></td>
                                <td><?= (int)$ingredient["isAccepted"] === 0 ? "<i class=\"bi bi-x-circle-fill text-danger\"></i>" :  "<i class=\"bi bi-check-circle-fill text-success\"></i>" ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-5 text-center">
        <div class="col-12">
            <button class="btn btn-outline-dark" id="add-ingredient-form-nav">Hozzáadás</button>
        </div>
    </div>
</div>


<?php include 'app/views/pages/private/user/ingredients/New_Ingredient.php' ?>