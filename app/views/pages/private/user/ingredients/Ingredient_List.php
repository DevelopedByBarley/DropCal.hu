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
                                <td><?= $ingredient["unit_quantity"] . "" . $ingredient["unit"] ?></td>
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

                                <td>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        Törlés
                                    </button>
                                </td>
                                <td>
                                    <a href="/ingredient/update?id=<?= $ingredient["ingredientId"] ?>" class="btn btn-warning">Szerkesztés</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-5 text-center">
        <div class="col-12">
            <a href="/ingredient" class="btn btn-outline-dark">Hozzáadás</a>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Figyelem!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Biztosan törlöd a <b style="font-size: 1rem" class="text-info"> <?= $ingredient["ingredientName"] ?> </b> nevű ételt?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                <a href="/ingredient/delete/<?= $ingredient["ingredientId"] ?>" class="btn btn-primary">Törlés</a>
            </div>
        </div>
    </div>
</div>