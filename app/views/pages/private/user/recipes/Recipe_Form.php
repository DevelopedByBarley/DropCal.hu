

<div class="container">
	<div class="row  mt-5">
		<div class="col-12 mb-5">
			<h1 class="display-4">Recept hozzáadása</h1>
		</div>
		<div class="col-12">
			<form action="/user/recipe/new" method="POST" enctype="multipart/form-data">
				<div class="row mb-4">
					<div class="col">
						<div class="form-outline">
							<label class="form-label" for="form6Example1">Recept neve</label>
							<input type="text" id="form6Example1" class="form-control" placeholder="Recept neve" name="name" required />
						</div>
					</div>
				</div>

				<div class="row mb-3">
					<label class="form-label" for="form6Example1">Összetevők</label>
					<div class="col-12 mt-3">
						<div id="ingredient-list-container">

						</div>
					</div>
					<input type="hidden" name="ingredients" id="ingredients"/>
				</div>

				<div class="row mb-4 ">

					<div class="col-12 border p-5 text-center">
						<div class="form-outline">
							<button type="button" class="btn btn-primary" onclick="renderModal()">Összetevő hozzáadása</button>
						</div>
					</div>
				</div>

				<div class="row mb-4 ">
					<label class="form-label" for="form6Example1">Lépések</label>
					<div class="col-12 border p-3 text-center mt-5">
						<div id="steps-container"></div>
						<div class="form-outline">
							<button class="btn btn-primary" id="step"><i class="bi bi-plus"></i> Lépés</button>
						</div>
					</div>
				</div>
				<div class="row mb-4 ">
					<label class="form-label" for="form6Example1">Fényép</label>
					<div class="col-12 border p-5 text-center">
						<div class="form-outline">
							<input type="file" id="form6Example1" class="form-control" id="file" name="files[]" />
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
					</div>
					<div class="col-12 col-md-4 border rounded d-flex align-items-center justify-content-center flex-column">
						<h4>Allergének</h4>
						<div id="recipe-allergens-container"></div>
					</div>
					<div class="col-12 col-md-4 border rounded d-flex align-items-center justify-content-center flex-column">
						<h4>Tipus</h4>
					</div>
				</div>

				<button type="submit" class="btn btn-primary btn-block mb-4">Recept elküldése</button>
			</form>
		</div>
	</div>
</div>



<div id="modal-container"></div>


<script src="public/js/recipes/recipe.js"></script>
