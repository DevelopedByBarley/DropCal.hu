    <div class="container">
        <div class="row">
            <div class="col-12">
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
                                    <td><?= $ingredient["allergens"] ?></td>
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


   <?php include 'app/views/pages/user/ingredients/New_Ingredient.php'?>

