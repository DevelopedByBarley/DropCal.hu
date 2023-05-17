<?php
$recipeForUpdate = $params["recipeForUpdate"] ?? null;

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
			<form action="<?= isset($recipeForUpdate) ? "/user/recipe/update/" . $recipeForUpdate["recipeId"] : '/user/recipe/new'?>" method="POST" enctype="multipart/form-data">
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

				<div class="row">
					<div class="col-12">
						<h1 class="display-5 text-center mt-5 mb-4">Milyen étkezésre ajánlod? <span><i style="font-size: .5rem; position: relative; top: -40px" class="bi bi-asterisk"></i></span></h1>
						<div class="input-group d-flex align-items-center justify-content-center">
							<input type="radio" class="btn-check" name="meal" id="breakfast" autocomplete="off" value="breakfast" required>
							<label class="btn btn-outline-secondary m-1" for="breakfast">Reggeli</label>

							<input type="radio" class="btn-check" name="meal" id="snack1" autocomplete="off" value="snack1" required>
							<label class="btn btn-outline-secondary m-1" for="snack1">Tízórai</label>

							<input type="radio" class="btn-check" name="meal" id="lunch" autocomplete="off" value="lunch" required>
							<label class="btn btn-outline-secondary m-1" for="lunch">Ebéd</label>

							<input type="radio" class="btn-check" name="meal" id="snack2" autocomplete="off" value="snack2" required>
							<label class="btn btn-outline-secondary m-1" for="snack2">Uzsonna</label>

							<input type="radio" class="btn-check" name="meal" id="dinner" autocomplete="off" value="dinner" required>
							<label class="btn btn-outline-secondary m-1" for="dinner">Vacsora</label>

							<input type="radio" class="btn-check" name="meal" id="snack3" autocomplete="off" value="snack3" required>
							<label class="btn btn-outline-secondary m-1" for="snack3">Nasi</label>
						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-12">
						<h1 class="display-5 text-center mt-5 mb-4">Milyen étrendet képvisel a recepted? <span><i style="font-size: .5rem; position: relative; top: -40px" class="bi bi-asterisk"></i></h1>
						<div class="input-group d-flex align-items-center justify-content-center">
							<input type="radio" class="btn-check" name="diet" id="general" autocomplete="off" value="general" required>
							<label class="btn btn-outline-secondary m-1" for="general">Általános</label>

							<input type="radio" class="btn-check" name="diet" id="meat" autocomplete="off" value="meat" required>
							<label class="btn btn-outline-secondary m-1" for="meat">Húsimádó</label>

							<input type="radio" class="btn-check" name="diet" id="vegetarian" autocomplete="off" value="vegetarian" required>
							<label class="btn btn-outline-secondary m-1" for="vegetarian">Vegetáriánus</label>

							<input type="radio" class="btn-check" name="diet" id="vegan" autocomplete="off" value="vegan" required>
							<label class="btn btn-outline-secondary m-1" for="vegan">Vegán</label>

							<input type="radio" class="btn-check" name="diet" id="paleo" autocomplete="off" value="paleo" required>
							<label class="btn btn-outline-secondary m-1" for="paleo">Paleo</label>

							<input type="radio" class="btn-check" name="diet" id="ketogenic" autocomplete="off" value="ketogenic" required>
							<label class="btn btn-outline-secondary m-1" for="ketogenic">Ketogén</label>
						</div>
					</div>
				</div>




				<div class="row mb-4 mt-5 ">
					<label class="form-label" for="form6Example1">Fénykép <span><i style="font-size: .5rem; position: relative; top: -7px" class="bi bi-asterisk"></i></label>
					<div class="col-12 border p-5 text-center">
						<div class="form-outline">
							<input type="file" id="form6Example1" class="form-control" id="file" name="files[]" <?php echo isset($recipeForUpdate) ? '' : 'required'?> multiple />
						</div>
					</div>
				</div>

				<div class="row mb-4">
					<div class="col">
						<div class="form-outline">
							<label class="form-label" for="form6Example1">Videó URL</label>
							<input type="text" id="video" class="form-control" placeholder="Video URL" name="video" />
						</div>
					</div>
				</div>

				<div class="row border text-center mb-4 bg-dark text-light p-3">
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

				<div class="row mb-3">
					<div class="col-12 border border-warning p-2">

						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="isPublic" name="isPublic">
							<label class="form-check-label" for="isPublic">Publikálom a receptet</label>
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-block mb-4">Recept elküldése</button>
			</form>
		</div>
	</div>
</div>



<div id="modal-container"></div>


<script src="public/js/recipes/recipe.js"></script>