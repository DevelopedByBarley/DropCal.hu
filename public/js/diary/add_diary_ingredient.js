//Home.php
const diaryToggle = document.getElementById("diary-ingredients-toggle");
const cancelIngredients = document.getElementById("cancel-ingredients");
const searchBtn = document.getElementById("search-ingredient");
const searchResultContainer = document.getElementById(
  "search-result-container"
);
const diaryIngredientForm = document.getElementById("single-ingredient-form");
let ingredientItems = document.querySelectorAll('.ingredient-item');
let searchResult = [];
let state = [];


// A már naplózott elemeken való végig iterálás
ingredientItems.forEach((item) => {
  item.addEventListener('click', (event) => {
    // Id alapján lekérjük az ingredientet
    let id = event.currentTarget.dataset.id;
    getDiaryIngredientById(`/api/diary-ingredient/${id}`, true);
  })
})


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
    ingredientItems.forEach((ingredientItem) => {
      ingredientItem.addEventListener("click", (event) => {
        let id = event.currentTarget.dataset.id;
        getDiaryIngredientById(`/api/ingredient-single/${id}`, false);;
      })
    })
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

// Diary Ingredient lekérése url alapján illetve hogy updatelünk vagy sem

function getDiaryIngredientById(url, isIngredientForUpdate) {
  fetch(url)
    .then((res) => res.json())
    .then((data) => {
      if (data) {

        isIngredientForUpdate ? state = {
          ingredientForUpdate: data
        } :
          state = {
            ingredient: data,
          };

        // Majd a diaryIngredientForm  megkapja az aktív class-t

        diaryIngredientForm.classList.add("active");
        // Amiben a kirajzolt tartalomért a renderDiaryIngredientForm() felel
        renderDiaryIngredientForm();
      }
    });
}

// A napló hozzáadásához való form kirajzolása, 
function renderDiaryIngredientForm() {
  let template = generateDiaryFormTemplate();
  diaryIngredientForm.innerHTML = template;

  const DiaryIngredientContainer = document.getElementById(
    "diary-ingredient-container"
  );


  // Kirajzold formból kikérjük az elemeket
  const DiaryBtn = document.getElementById("diary-btn");
  const DataBtn = document.getElementById("data-btn");
  const Units = document.querySelectorAll(".units");
  const Quantity = document.getElementById("quantity");

  const ResultOfCalorie = document.getElementById("result-of-calorie");
  const ResultOfProtein = document.getElementById("result-of-protein");
  const ResultOfCarb = document.getElementById("result-of-carb");
  const ResultOfFat = document.getElementById("result-of-fat");
  const sendBtn = document.getElementById("send");



  // Kalkulátorokkal kirajzoljuk a kikért elemek értékeit
  ResultOfCalorie.innerHTML = calculateCalorie(Quantity.value)
  ResultOfProtein.innerHTML = calculateMacros(Quantity.value).protein;
  ResultOfCarb.innerHTML = calculateMacros(Quantity.value).carb;
  ResultOfFat.innerHTML = calculateMacros(Quantity.value).fat



  // Input mező input eseményére reagálva szintén kalkulátorokkal kalkulálunk és kirajzoljuk azokat!
  Quantity.oninput = (event) => {
    let quantity = parseInt(event.target.value);
    if (isNaN(quantity)) quantity = 0;
    localStorage.setItem("quantity", quantity);


    ResultOfCalorie.innerHTML = calculateCalorie(quantity);
    ResultOfProtein.innerHTML = calculateMacros(quantity).protein;
    ResultOfCarb.innerHTML = calculateMacros(quantity).carb;
    ResultOfFat.innerHTML = calculateMacros(quantity).fat
  }

  // Unit buttonokra való reagálás, active classt megkapják és a kiválasztott unit multiplier alapján rajzolódnak ki az értékek
  Units.forEach((unitBtn) => {
    unitBtn.onclick = (event) => setUnitButton(event);
  });

  if (DiaryBtn) {
    DiaryBtn.onclick = () => renderDiaryIngredientForm();
  }
  if (DataBtn) {
    DataBtn.onclick = () => renderData(DiaryIngredientContainer);
  }

  // Küldés a backendnek ÚJ ingredient hozzáadása esetén vagy UPDATE esetén
  sendBtn.addEventListener('click', (event) => {
    event.preventDefault();
    const Ingredient = state.hasOwnProperty("ingredientForUpdate")
      ? state.ingredientForUpdate
      : state.ingredient
    let isIngredientForUpdate = state.hasOwnProperty("ingredientForUpdate"); // Megnézzük hogy a state feltöltése updatelni való ingredientből vagy nem
    let date = event.target.parentElement.parentElement.parentElement.dataset.date


    let newIngredient = {
      name: Ingredient.ingredientName,
      unit: Ingredient.unit,
      unitQuantity: Quantity.value,
      commonUnit: Ingredient.common_unit,
      common_unit_quantity: parseInt(Ingredient.common_unit_quantity),
      common_unit_ex: Ingredient.common_unit_ex,
      partOfTheDay: 1,
      selectedUnit: Ingredient.ingredientUnits.find(ingredient => ingredient.isSelected === true),
      calorie: Ingredient.calorie,
      protein: Ingredient.protein,
      carb: Ingredient.carb,
      fat: Ingredient.fat,
      glychemicIndex: Ingredient.glycemicIndex,
      currentCalorie: parseInt(event.target.parentElement.parentElement.querySelector("#result-of-calorie").innerHTML),
      currentProtein: parseInt(event.target.parentElement.parentElement.querySelector("#result-of-protein").innerHTML),
      currentCarb: parseInt(event.target.parentElement.parentElement.querySelector("#result-of-carb").innerHTML),
      currentFat: parseInt(event.target.parentElement.parentElement.querySelector("#result-of-fat").innerHTML),
      diaryRefId: parseInt(event.target.parentElement.parentElement.parentElement.dataset.diaryid),
    }

    let id = Ingredient.d_ingredientId;

    if (isIngredientForUpdate) {
      fetchIngredient(`/api/ingredient-update/${id}`, newIngredient, date)
      return;
    }
    fetchIngredient("/api/ingredient-new", newIngredient, date);
  })
}


// Ingredient lekérése
function fetchIngredient(url, body, date) {

  fetch(url, {
    method: "POST",
    body: JSON.stringify(body)
  })
    .then(res => res.json())
    .then(data => {
      let state = data.state;

      if (state) {
        window.location.href = `/diary/currentDiary?date=${date}`
      }
    });
}



















// ---------------->Calculate Section;------------------------------------>

// Kalória kalkulálása
function calculateCalorie(quantity) {
  const Units = state.hasOwnProperty("ingredientForUpdate")
    ? state.ingredientForUpdate.ingredientUnits
    : state.ingredient.ingredientUnits;
  let ingredient = state.hasOwnProperty("ingredientForUpdate")
    ? state.ingredientForUpdate
    : state.ingredient;
  let unit = Units.find(unit => unit.isSelected === true);
  let multiplier = parseInt(unit.multiplier);
  let kCal = parseInt(ingredient.calorie);


  let calculated = ((parseInt(quantity) * multiplier) / 100) * kCal;

  return Math.round(calculated);
}


// Makrók kalulálása
function calculateMacros(quantity) {
  const Units = state.hasOwnProperty("ingredientForUpdate")
    ? state.ingredientForUpdate.ingredientUnits
    : state.ingredient.ingredientUnits;
  let ingredient = state.hasOwnProperty("ingredientForUpdate")
    ? state.ingredientForUpdate
    : state.ingredient;
  let unit = Units.find(unit => unit.isSelected === true);
  let multiplier = parseInt(unit.multiplier);
  let protein = Math.round(((parseInt(quantity) * multiplier) / 100) * parseInt(ingredient.protein));
  let carb = Math.round(((parseInt(quantity) * multiplier) / 100) * parseInt(ingredient.carb));
  let fat = Math.round(((parseInt(quantity) * multiplier) / 100) * parseInt(ingredient.fat));

  return {
    protein: protein,
    carb: carb,
    fat: fat
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
  let quantity = localStorage.getItem("quantity") ? localStorage.getItem("quantity") : ingredient.unit_quantity;


  template += `
    <div class="row mt-2 diary-form">
        <h1 class="display-5 mt-2 mb-2" id="name">${ingredient.ingredientName}</h1>
     
        <div class="btn-group d-flex align-items-center justify-content-center" role="group" aria-label="Basic mixed styles example">
          <button type="button" class="btn btn-info text-light m-3" id="diary-btn">Napló</button>
          <button type="button" class="btn btn-warning text-light m-3" id="data-btn">Adatok</button>
         </div>
        <hr>
        </div>
        <div class="row" id="diary-ingredient-container"> 
          <div class="col-12">
            <input type="number" id="quantity" name="quantity" value="${quantity}"/>
          </div>

          <div class="col-12 mt-3 mb-3">
            ${unitTemplate}
          </div>
          
          <div class="col-12 calorie border p-1 mb-2" style="font-size: 1.2rem">
            <span id="result-of-calorie" ></span> Kcal
          </div>

          <div class="col-4 macros" >
            <h5>Protein</h5> 
            <span id="result-of-protein"></span>g
          </div>
          <div class="col-4 macros" >
            <h5>Szénhidrát</h5>  
            <span id="result-of-carb"></span>
          </div>
          <div class="col-4 macros" >
            <h5>Zsir</h5> 
            <span id="result-of-fat"></span>
          </div>
          <div class="col-12 mt-4">
            <button class="btn ${isIngredientForUpdate ? 'btn-warning' : 'btn-primary'} text-light" id="send">${isIngredientForUpdate ? 'Frissit' : 'Hozzáad  '}</button>
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



  if (ingredient.allergens) {
    ingredient.allergens.forEach((allergen) => {
      allergensTemplate += `
        <span class="text-warning"> ${allergen.i_allergenNumber}, </span>
      `;
    });
  }

  let diaryDataTemplate = `
    <div class="row text-center mt-2">
      <h1 class="display-6 mb-3">Adatok/100g</h1>
      <div class="col-4">
          <h6>Protein <br> ${ingredient.protein}g</h6>
      </div>
      <div class="col-4">
          <h6>Szénhidrát <br> ${ingredient.carb}g</h6>
      </div>
      <div class="col-4">
          <h6>Zsír <br>${ingredient.fat}g</h6>
      </div>
      <div class="col-6">
          <h6>Kalória  <br>${ingredient.calorie}</h6>
      </div>
      <div class="col-6">
        <h6>Glikémiás I <br>${ingredient.glycemicIndex ? ingredient.glycemicIndex : 0}</h6>
      </div> 
    </div>
    <hr>
    <div class="row">
     <h1 class="display-6 mb-3">Allergének</h1>
     <div class="col-12" >
      ${allergensTemplate}
     </div>
    </div>
  `;

  diaryIngredientContainer.innerHTML = diaryDataTemplate;
}
