<?php
$recipeForUpdate = $params["recipeForUpdate"] ?? null;
$recipeIngredientData = $recipeForUpdate["ingredientData"] ?? null;

?>


<?php if (isset($recipeForUpdate)) : ?>
	<div id="recipe-for-update" data-update='<?= json_encode($recipeForUpdate) ?>'></div>
<?php endif ?>

<div class="container">
	<div class="row  mt-5">
		<div class="col-12 mb-5">
			<h1 class="display-4">Recept hozzáadása</h1>
		</div>
		<div class="col-12">
			<form action="<?= isset($recipeForUpdate) ? "/user/recipe/update/" . $recipeForUpdate["recipeId"] : '/user/recipe/new' ?>" method="POST" enctype="multipart/form-data">
				<div class="row mb-4">
					<div class="col">
						<div class="form-outline">
							<label class="form-label" for="form6Example1">Recept neve</label> <span><i style="font-size: .5rem; position: relative; top: -7px" class="bi bi-asterisk"></i></span>
							<input type="text" id="recipe-name" class="form-control" placeholder="Recept neve" name="name" value="<?= isset($recipeForUpdate) ? $recipeForUpdate["recipe_name"] : '' ?>" required oninput="setRecipeName(event)" />
						</div>
					</div>
				</div>

				<div class="row mb-1">
					<label class="form-label" for="form6Example1">Összetevők <span><i style="font-size: .5rem; position: relative; top: -7px" class="bi bi-asterisk"></i></span></label>
					<div class="col-12 ">
						<div id="ingredient-list-container">

						</div>
					</div>
					<input type="hidden" name="ingredients" id="ingredients" required />
				</div>

				<div class="row mb-4 mt-3 mb-5 d-flex align-items-center justify-content-center">
					<div class="col-12 col-sm-5 mt-2  p-1 text-center" id="search-box-container">
						<div class="form-outline">
							<button type="button" class="btn btn-primary" onclick="showSearchBox()">Összetevő hozzáadása</button>

						</div>
					</div>
				</div>

				<div class="row mb-4 ">
					<label class="form-label" for="form6Example1">Lépések <span><i style="font-size: .5rem; position: relative; top: -7px" class="bi bi-asterisk"></i></span></label>
					<div class="col-12 border p-3 text-center">
						<div id="steps-container"></div>
						<div class="form-outline">
							<button class="btn btn-primary" id="step"><i class="bi bi-plus"></i> Lépés</button>
						</div>
					</div>
				</div>





				<div class="row mb-4 mt-5">

					<div class="col-12 col-sm-6  mb-2">
						<div class="form-outline mb-4 w-100">
							<label class="form-label" for="form1Example2">Kategória</label>
							<select class="form-select" aria-label="Default select example" id="ingredientCategorie" name="ingredientCategorie" required>
								<option value="" disabled selected>Választ</option>$
								<?php foreach ($params["ingredientCategories"] as $categorie) : ?>
									<option value="<?= $categorie ?>" <?php echo (isset($recipeForUpdate) && $recipeIngredientData["ingredientCategorie"] === $categorie) ? "selected" : "" ?>> <?= $categorie ?></option>
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
									<option value="<?= $unit ?>" <?php echo (isset($recipeForUpdate) && $recipeIngredientData["ingredientUnit"] === $unit) ? "selected" : "" ?>> <?= $unit ?></option>
								<?php endforeach ?>

							</select>
						</div>
					</div>
					<hr class="mt-3 mb-3">
					<div class="row mb-4">
						<div class="col-xs-12 col-lg-3 d-flex align-items-center justify-content-center mb-2">
							<div class="form-check form-switch">
								<input class="form-check-input mb-4" style="font-size: 1.5rem; position: relative; top: -5px;" type="checkbox" id="common_unit_check" <?php echo (isset($recipeForUpdate) && $recipeIngredientData["common_unit"] !== '') ? "checked" : "" ?> />
								<label class="form-check-label" for="form11Example4">
									Gyakori egység megadása
								</label>
							</div>
						</div>
						<div class="col-xs-12 col-lg-4 mb-5">
							<label class="form-label" for="form1Example1">Gyakori egység</label>
							<select class="form-select" id="common_unit" aria-label="Default select example" name="common_unit" disabled>
								<option value="" disabled selected>Választ</option>
								<?php foreach ($params["common_units"] as $unit) : ?>
									<option value="<?= $unit ?>" <?php echo (isset($recipeForUpdate) && $unit === $recipeIngredientData["common_unit"]) ? "selected" : "" ?>> <?= $unit ?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="col-xs-12 col-lg-4 mb-5">
							<label class="form-label" for="form1Example1">Gyakor egység súlya (g / ml)</label>
							<input required type="number" min="1" id="common_unit_quantity" class="form-control" placeholder="Gyakori egység súlya" style="background: none; border: none; border-bottom: 1px solid" name="common_unit_quantity" value="<?= isset($recipeIngredientData) ? $recipeIngredientData["common_unit_quantity"] : '' ?>" disabled />
						</div>
					</div>
				</div>




				<div class="row">
					<div class="col-12">
						<h1 class="display-5 text-center mt-5 mb-4">Milyen étkezésre ajánlod? <span><i style="font-size: .5rem; position: relative; top: -40px" class="bi bi-asterisk"></i></span></h1>
						<div class="input-group d-flex align-items-center justify-content-center">
							<?php foreach ($params["meals"] as $meal) : ?>
								<input type="checkbox" class="btn-check" name="meal[]" id="<?= $meal ?>" autocomplete="off" value="<?= $meal ?>" <?php echo isset($recipeForUpdate) && !empty($recipeForUpdate["meals"]) && in_array($meal, $recipeForUpdate["meals"]) ? 'checked' : '' ?>>
								<label class="btn btn-outline-dark m-1" for="<?= $meal ?>"><?= $meal ?></label>
							<?php endforeach ?>


						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-12">
						<h1 class="display-5 text-center mt-5 mb-4">Milyen étrendet képvisel a recepted? <span><i style="font-size: .5rem; position: relative; top: -40px" class="bi bi-asterisk"></i></h1>
						<div class="input-group d-flex align-items-center justify-content-center">
							<?php foreach ($params["diets"] as $diet) : ?>
								<input type="checkbox" class="btn-check" name="diet[]" id="<?= $diet ?>" autocomplete="off" value="<?= $diet ?>" <?php echo isset($recipeForUpdate) && !empty($recipeForUpdate["diets"]) && in_array($diet, $recipeForUpdate["diets"]) ? 'checked' : '' ?>>
								<label class="btn btn-outline-dark m-1" for="<?= $diet ?>"><?= $diet ?></label>
							<?php endforeach ?>
						</div>
					</div>
				</div>

				<div class="col-xs-12 d-flex align-items-center justify-content-center mt-5">
					<div class="form-check form-switch">
						<label class="form-check-label" for="isRecipeSugarFree" style="position: relative; top: 10px;">
							<b> Cukormentes recept</b>
						</label>
						<input class="form-check-input" type="checkbox" id="isRecipeSugarFree" name="isForDiab" style="font-size: 1.7rem; cursor: pointer" <?php echo isset($recipeForUpdate) &&  (int)$recipeForUpdate["isForDiab"] === 1 ? 'checked' : '' ?> />
					</div>
				</div>


				<div class="row mb-4 mt-5 ">
					<label class="form-label" for="form6Example1">Fénykép <span><i style="font-size: .5rem; position: relative; top: -7px" class="bi bi-asterisk"></i></label>
					<div class="col-12 border p-5 text-center">
						<div class="form-outline">
							<input type="file" id="form6Example1" class="form-control" id="file" name="files[]" max="4" <?php echo isset($recipeForUpdate) ? '' : 'required' ?> multiple />
						</div>
					</div>
				</div>

				<div class="row mb-4">
					<div class="col">
						<div class="form-outline">
							<label class="form-label" for="form6Example1">Videó URL</label>
							<input type="text" id="video" class="form-control" placeholder="Video URL" name="video" value="<?php echo isset($recipeForUpdate) && isset($recipeForUpdate["video"]) ? $recipeForUpdate["video"] : "" ?>" />
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-outline mb-4">
						<label class="form-label" for="form4Example3">Leirás</label>
						<textarea class="form-control" id="form4Example3" rows="4" name="description" required>
							<?php echo isset($recipeForUpdate) ? $recipeForUpdate["description"] : '' ?>
						</textarea>
					</div>
				</div>

				<div class="row border text-center bg-dark text-light p-3">
					<div class="col-12 mt-4 mb-4">
						<h1 class="display-6">Összegzés</h1>
					</div>
					<div class="col-12 col-md-4 border rounded d-flex align-items-center justify-content-center flex-column">
						<h4>Adatok</h4>
						<div class="row">
							<div class="col-12">
								<h6>Kalória</h6>
								<span><b id="calorie-container">0</b> Kcal</span>
							</div>
						</div>
						<div class="row w-100 mt-3">
							<div class="col-12 col-sm-4 border p-3">
								<p>Fehérje</p>
								<div><b id="protein-container">0</b>g</div>
							</div>
							<div class="col-12 col-sm-4 border p-3">
								<p>Szénhidrát</p>
								<div><b id="carb-container">0</b>g</div>
							</div>
							<div class="col-12 col-sm-4 border p-3">
								<p>Zsir</p>
								<div><b id="fat-container">0</b>g</div>
							</div>
						</div>
						<input type="hidden" name="macros" id="macros-input" />
					</div>
					<div class="col-12 col-md-4 border rounded d-flex align-items-center justify-content-center flex-column">
						<h4>Allergének</h4>
						<div id="recipe-allergens-container"></div>
						<input type="hidden" name="allergens" id="allergens-input" />
					</div>

					<div class="col-12 col-md-4 border rounded d-flex align-items-center justify-content-center flex-column">
						<h4>Glikémiás index átlag</h4>
						<div id="glycemic-index-container"></div>
						<input type="hidden" name="glycemic_index_summary" id="glycemic-index-summary" />
					</div>

				</div>


				<div class="row mb-5 mt-4 p-2 text-light bg-danger">
					<div class="col-12 p-2">
						<div class="col-xs-12 d-flex align-items-center justify-content-center">
							<div class="form-check form-switch">
								<label class="form-check-label" for="isRecipeSugarFree" style="position: relative; top: 10px;">
									<b> Publikálásra ajánlom a receptet!</b>
								</label>
								<input class="form-check-input" type="checkbox" id="isRecommended" name="isRecommended" style="font-size: 1.7rem; cursor: pointer" <?php echo  isset($recipeForUpdate) && (int)$recipeForUpdate["isRecommended"] === 1 ? 'checked' : '' ?> />
							</div>
						</div>
					</div>
				</div>

				<div class="text-center">
					<button type="submit" class="btn btn-outline-dark btn-block mb-4 mt-5">Recept elküldése</button>
				</div>
			</form>
		</div>
	</div>
</div>



<div id="modal-container"></div>


<script src="public/js/recipes/recipe.js"></script>