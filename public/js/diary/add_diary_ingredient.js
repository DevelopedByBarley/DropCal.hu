//Home.php
const diaryToggle = document.getElementById("diary-ingredients-toggle");
const cancelIngredients = document.getElementById("cancel-ingredients");
const searchBtn = document.getElementById("search-ingredient");
const searchResultContainer = document.getElementById(
  "search-result-container"
);
const diaryIngredientForm = document.getElementById("single-ingredient-form");
let searchResult = [];
let state = [];

//------------->Search section

function searchIngredients(event) {
  //I. Input mezőbe írunk valamit lekérjük az adatbázist és feltöltjük a state-t
  let name = event.target.value;
  if (name.length >= 2) {
    fetch(`/api/search/${name}`)
      .then((res) => res.json())
      .then((data) => (searchResult = data));

    // II. A feltöltött stat-el kirajzoljuk a listát
    renderSearchResult();

    // III. A listaelemeket bekérjük és átadjuk a getIngredientByName()-nak amelyel
    let ingredientItems = document.querySelectorAll(".ingredient-item");

    getIngredientById(ingredientItems);
  }

  // IV. Kitisztítjuk a Container-t ha az input value length 0
  clearContainer(name);
}

// Gombnyomásra való keresés
if (searchBtn) {
  searchBtn.addEventListener("click", (event) => {
    let name = event.target.parentElement.parentElement.childNodes[1].value;
    if (name.length >= 2) {
      fetch(`/api/search/${name}`)
        .then((res) => res.json())
        .then((data) => (searchResult = data));
    }
  });
}

// Toggle button
if (diaryToggle) {
  diaryToggle.addEventListener("click", () => {
    ingredientsToggle.classList.add("active");
  });
}
// Cancel button
if (cancelIngredients) {
  cancelIngredients.addEventListener("click", () => {
    ingredientsToggle.classList.remove("active");
  });
}

// Render search result a container-be
function renderSearchResult() {
  let searchResultTemplate = ``;

  searchResult.forEach((ingredient) => {
    searchResultTemplate += `
    <li class="list-group-item ingredient-item w-100" data-id="${ingredient.ingredientId}" style="cursor:pointer;">${ingredient.ingredientName}</li>
      `;
  });
  searchResultContainer.innerHTML = searchResultTemplate;
}

function clearContainer(name) {
  // State és result-container kiürítése ha nincs az inputban érték
  if (name.length === 0) {
    searchResult = [];
    searchResultContainer.innerHTML = "";
  }
}

//--------------------------------------------------------------------------------------------------------------------->

// Diary Ingredient section ------------------------------------------------------------------------------------------------------------------------->
function getIngredientById(ingredientItems) {
  // Végig lépdelünk az ingredient itemeken amiket a search resultba vissza kaptunk
  ingredientItems.forEach((ingredientItem) => {
    ingredientItem.addEventListener("click", (event) => {
      // Kiürítjük clickre a containert
      event.target.parentElement.innerHTML = "";

      // Id alapján lekérjük az ingredientet
      let id = event.target.dataset.id;
      fetch(`/api/ingredient-single/${id}`)
        .then((res) => res.json())
        .then((data) => {
          if (data) {
            state = {
              //ingredientForUpdate: data,
              ingredient: data,
            };

            // Majd a diaryIngredientForm  megkapja az aktív class-t

            diaryIngredientForm.classList.add("active");
            // Amiben a kirajzolt tartalomért a renderDiaryIngredientForm() felel
            renderDiaryIngredientForm();
          }
        });
    });
  });
}

// A napló hozzáadásához való form kirajzolása
function renderDiaryIngredientForm() {
  let template = generateDiaryFormTemplate();
  diaryIngredientForm.innerHTML = template;

  const DiaryIngredientContainer = document.getElementById(
    "diary-ingredient-container"
  );
  const DiaryBtn = document.getElementById("diary-btn");
  const DataBtn = document.getElementById("data-btn");
  const Units = document.querySelectorAll(".units");

  Units.forEach((unitBtn) => {
    unitBtn.onclick = (event) => setUnitButton(event);
  });

  if (DiaryBtn) {
    DiaryBtn.onclick = () => renderDiaryIngredientForm();
  }
  if (DataBtn) {
    DataBtn.onclick = () => renderData(DiaryIngredientContainer);
  }
}

// -------------------UNIT SECTION------------------------------------------------------------------------------------------------->

// Aktív class beállítása gombnyomásra
function setUnitButton(event) {
  const Units = state.hasOwnProperty("ingredientForUpdate")
    ? state.ingredientForUpdate.ingredientUnits
    : state.ingredient.ingredientUnits;
  let index = event.target.dataset.index;
  let selectedIndex = Units.findIndex(
    (unit) => parseInt(unit.index) === parseInt(index)
  );

  for (i = 0; i < Units.length; i++) {
    if (selectedIndex === i) {
      Units[i].isSelected = true;
    } else {
      Units[i].isSelected = false;
    }
  }
  renderDiaryIngredientForm();
}












// -----------------TEMPLATE SECTION--------------------------------------------------------------------->

// UnitButtons kigenerálása
const generateUnitTemplate = (Units) => {
  let unitTemplate = "";

  Units.forEach((unit) => {
    unitTemplate += `<button data-index="${unit.index
      }" class="units btn btn-outline-warning text-light btn-md m-1 ${unit.isSelected ? "active" : ""
      }">${unit.unitName}</button>`;
  });

  return unitTemplate;
};

//  template kigenerálása
function generateDiaryFormTemplate() {
  const Units = state.hasOwnProperty("ingredientForUpdate")
    ? state.ingredientForUpdate.ingredientUnits
    : state.ingredient.ingredientUnits;
  let isIngredientForUpdate = state.hasOwnProperty("ingredientForUpdate"); // Megnézzük hogy a state feltöltése updatelni való ingredientből vagy nem
  let ingredient = state.hasOwnProperty("ingredientForUpdate")
    ? state.ingredientForUpdate
    : state.ingredient; // Ennek függvényében töltjük fel az ingredient értékét
  let template = "";
  let unitTemplate = generateUnitTemplate(Units);

  template += `
    <div class="row mt-2 diary-form">
        <h1 class="display-5 mt-2 mb-2">${ingredient.ingredientName}</h1>
     
        <div class="btn-group d-flex align-items-center justify-content-center" role="group" aria-label="Basic mixed styles example">
          <button type="button" class="btn btn-info text-light m-3" id="diary-btn">Napló</button>
          <button type="button" class="btn btn-warning text-light m-3" id="data-btn">Adatok</button>
         </div>
        <hr>
        </div>
        <div class="row" id="diary-ingredient-container"> 
          <div class="col-12">
            <input type="number" id="quantity" name="quantity"/>
          </div>
          <div class="col-12">
            ${unitTemplate}
          </div>  

          <div class="col-12">
            ${isIngredientForUpdate
      ? `<button class="btn btn-warning text-light">Frissít</button>`
      : `<button class="btn btn-primary text-light">Hozzáad</button>`
    }
          </div>
        </div>
  `;
  // Templateben lévő diary-ingredient-containerben generáljuk ki a napló vagy adatok gombra kattintva a kívánt tartalmat
  return template;
}

// Ha az "Adatok" gombra kattintunk generáljuk ki ezt a tartalmat
function renderData(diaryIngredientContainer) {
  let ingredient = state.hasOwnProperty("ingredientForUpdate")
    ? state.ingredientForUpdate
    : state.ingredient;
  let allergensTemplate = "";
  ingredient.allergens.forEach((allergen) => {
    allergensTemplate += `
      <div class="btn btn-outline-warning w-100">${allergen.i_allergenName}</div>
    `;
  });

  let diaryDataTemplate = `
    <div class="row mt-2">
      <h1 class="display-6 mb-3">Adatok</h1>
      <div class="col-6 mb-2">
        <div class="btn btn-outline-light w-100">
          <p>Kalória</p>
          <h6>${ingredient.calorie}</h6>
        </div>
      </div>
      <div class="col-6 mb-2">
        <div class="btn btn-outline-light w-100">
          <p>Protein</p>
          <h6>${ingredient.protein}</h6>
        </div>
      </div>
      <div class="col-6 mb-2">
        <div class="btn btn-outline-light w-100">
          <p>Szénhridrát</p>
          <h6>${ingredient.carb}</h6>
        </div>
      </div>
      <div class="col-6 mb-2">
        <div class="btn btn-outline-light w-100">
          <p>Zsír</p>
          <h6>${ingredient.fat}</h6>
        </div>
      </div>
      <div class="col-6 mb-2">
        <div class="btn btn-outline-light w-100">
          <p>Glikémiás Index</p>
          <h6>${ingredient.glycemicIndex}</h6>
        </div>
      </div>
    </div>
    <hr>
    <div class="row mt-5">
     <h1 class="display-6 mb-3">Allergének</h1>
     <div class="col-12">
      ${allergensTemplate}
     </div>
    </div>
  `;

  diaryIngredientContainer.innerHTML = diaryDataTemplate;
}
