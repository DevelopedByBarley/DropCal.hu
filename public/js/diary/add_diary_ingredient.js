//Home.php
const diaryToggle = document.getElementById("diary-ingredients-toggle");
const cancelIngredients = document.getElementById("cancel-ingredients");
const Search = document.getElementById("search");
const searchBtn = document.getElementById("search-ingredient");
const searchResultContainer = document.getElementById(
  "search-result-container"
);
const diaryIngredientForm = document.getElementById("staticBackdrop");
let ingredientItems = document.querySelectorAll('.ingredient-item');
let pageCounter = localStorage.getItem("page-counter") ? localStorage.getItem("page-counter") : 1;
let numberOfPage;
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

function searchIngredients() {
  //I. Input mezőbe írunk valamit lekérjük az adatbázist és feltöltjük a state-t
  let name = Search.value;

  if (name.length >= 2) {
    fetch(`/api/search/${name}`)
      .then((res) => res.json())
      .then((data) => {
        searchResult = data["ingredients"]
        numberOfPage = data["number_of_page"];
        localStorage.setItem('page-counter', numberOfPage)
        renderSearchResult();
      });

    // III. A listaelemeket bekérjük és átadjuk a getIngredientByName()-nak amelyel
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



// Render search result a container-be
function renderSearchResult() {
  let searchResultTemplate = ``;
  const PaginationTemplate = `
  <nav aria-label="Page navigation example">
    <ul class="pagination mt-3 mb-3">
      <li class="page-item">
        <a class="page-link ${pageCounter <= 1 ? 'disabled' : ''}" aria-label="Previous" style="cursor: pointer" id="prev">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
      <li class="page-item"><a class="page-link">${parseInt(pageCounter) === 0 ? 1 : pageCounter} / ${parseInt(numberOfPage) === 0 ? 1 : numberOfPage}</a></li>
      <li class="page-item">
        <a class="page-link ${pageCounter >= numberOfPage ? 'disabled' : ''}" aria-label="Next" style="cursor: pointer" id="next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </ul>
  </nav>

  `;

  if (searchResult.length > 0) {
    searchResultTemplate += PaginationTemplate;
  }
  searchResult.forEach((ingredient) => {
    searchResultTemplate += `
    <li class="list-group-item ingredient-item w-100" data-id="${ingredient.ingredientId ? ingredient.ingredientId : ingredient.recipeId}" style="cursor:pointer;">${ingredient.ingredientName ? ingredient.ingredientName : ingredient.recipe_name}</li>
      `;
  });
  searchResultContainer.innerHTML = searchResultTemplate;

  const Prev = document.getElementById('prev');
  const Next = document.getElementById('next');

  Prev.addEventListener('click', (event) => prev(event))

  Next.addEventListener('click', (event) => next(event))

  let ingredientItems = document.querySelectorAll(".ingredient-item");
  ingredientItems.forEach((ingredientItem) => {
    ingredientItem.addEventListener("click", (event) => {
      let id = event.currentTarget.dataset.id;
      getDiaryIngredientById(`/api/ingredient-single/${id}`, false);;
    })
  })

}

function prev(event) {
  event.preventDefault();
  let name = Search.value
  if (pageCounter > 1) {
    pageCounter--;
  }

  fetch(`/api/search/${name}?page=${pageCounter}`)
    .then((res) => res.json())
    .then((data) => {
      searchResult = data["ingredients"]
      numberOfPage = data["number_of_page"];
      renderSearchResult();
    });
}

function next(event) {
  event.preventDefault();
  let name = Search.value;


  if (pageCounter < numberOfPage) {
    pageCounter++;
  } else {
    pageCounter = numberOfPage
    renderSearchResult();
    return;
  }
  fetch(`/api/search/${name}?page=${pageCounter}`)
    .then((res) => res.json())
    .then((data) => {
      searchResult = data["ingredients"]
      numberOfPage = data["number_of_page"];
      renderSearchResult();
    });
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
      console.log(data);
      if (data) {

        isIngredientForUpdate ? state = {
          ingredientForUpdate: data
        } :
          state = {
            ingredient: data,
          };



        let modal = new bootstrap.Modal(diaryIngredientForm)
        modal.show();
        renderDiaryModal();
      }
    });
}

function generatePartsOfTheDayTemplate(parts) {
  let template = ``;
  parts.forEach(part => {

      template += `
        <input type="radio" class="btn-check parts" name="partOfTheDay" id="${part.name}" autocomplete="off" ${part.isSelected ? 'checked' : ''} value="${part.id}">
        <label class="btn btn-outline-${part.color}" for="${part.name}">${part.value}</label>
      `
  });

  return template;
}

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
  let partsOfTheDayTemplate = generatePartsOfTheDayTemplate(ingredient.partsOfTheDay);
  let quantity = localStorage.getItem("quantity") ? localStorage.getItem("quantity") : ingredient.unit_quantity;
  let allergensTemplate = "";


  if (ingredient.allergens) {

    ingredient.allergens.forEach((allergen) => {
      allergensTemplate += `
          <span class="text-warning"> ${allergen.i_allergenNumber}, </span>
        `;
    });
  } else {
    allergensTemplate += `
    <span class="text-warning"> Nincs allergén </span>
  `;
  }


  template += `
   

        <div class="modal-dialog">
        <div class="modal-content text-center ">
          <div class="modal-header text-center">
            <h1 class="modal-title display-6" id="name">
             ${ingredient.ingredientName}
            </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="diary-ingredient-container">
            <div class="col-12">
              <input type="number" id="quantity" name="quantity" value="${quantity}"/>
            </div>

            <div class="col-12 mt-3 mb-3 d-flex align-items-center justify-content-center">
              ${unitTemplate}
            </div>

            <div class="col-12 calorie border p-1 mb-2" style="font-size: 1.6rem">
              <b><span id="result-of-calorie" ></span> Kcal</b>
          </div>

          <div class="d-flex text-center">
            <div class="col-4 macros" >
              <h5>Protein</h5> 
              <span id="result-of-protein"></span>g
            </div>
            <div class="col-4 macros" >
              <h5>Szénhidrát</h5>  
              <span id="result-of-carb"></span>g
            </div>
            <div class="col-4 macros" >
              <h5>Zsir</h5> 
              <span id="result-of-fat"></span>g
            </div>
          </div>

         
          </div>
          <hr>
          <div class="col-12">
            ${partsOfTheDayTemplate}
        </div>
        
        <hr>
          <div class="row text-center mt-2">
            <div class="col-12">
              <h4>GI <br>${ingredient.glycemicIndex ? ingredient.glycemicIndex : 0}</h4>
            </div> 
          </div>
          <hr>
          <div class="row">
            <h4 class="mb-3">Allergének</h4>
            <div class="col-12" >
              ${allergensTemplate}
            </div>
          </div>
     
          
          <div class="modal-footer">
          <div class="col-12">
            <button data-id="${ingredient.d_ingredientId}" class="btn ${isIngredientForUpdate ? 'btn-warning' : 'btn-primary'} text-light" id="send">${isIngredientForUpdate ? 'Frissit' : 'Hozzáad  '}</button>
            ${isIngredientForUpdate ? ` <button class='btn btn-danger text-light' data-id="${ingredient.d_ingredientId}" id='delete'>Törlés</button>` : ""}
            </div>
          </div>
        </div>
  `;
  // Templateben lévő diary-ingredient-containerben generáljuk ki a napló vagy adatok gombra kattintva a kívánt tartalmat
  return template;
}

// A napló hozzáadásához való form kirajzolása, 
function renderDiaryModal() {

  let template = generateDiaryFormTemplate();
  diaryIngredientForm.innerHTML = template;

  // Kirajzold formból kikérjük az elemeket
  const DiaryBtn = document.getElementById("diary-btn");
  const Units = document.querySelectorAll(".units");
  const Parts = document.querySelectorAll(".parts");
  const Quantity = document.getElementById("quantity");
  const DeleteBtn = document.getElementById("delete");

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
    DiaryBtn.onclick = () => renderDiaryModal();
  }


  // Küldés a backendnek ÚJ ingredient hozzáadása esetén vagy UPDATE esetén
  sendBtn.addEventListener('click', (event) => {
    event.preventDefault();
    const Ingredient = state.hasOwnProperty("ingredientForUpdate")
      ? state.ingredientForUpdate
      : state.ingredient
    let isIngredientForUpdate = state.hasOwnProperty("ingredientForUpdate"); // Megnézzük hogy a state feltöltése updatelni való ingredientből vagy nem
    let date = event.target.parentElement.parentElement.parentElement.parentElement.parentElement.dataset.date
    let selectedPart;

    for (var i = 0; i < Parts.length; i++) {
      if (Parts[i].checked) {
        selectedPart = Parts[i].value;
        break;
      }
    }

    let newIngredient = {
      name: Ingredient.ingredientName,
      unit: Ingredient.unit,
      unitQuantity: Quantity.value,
      commonUnit: Ingredient.common_unit,
      common_unit_quantity: parseInt(Ingredient.common_unit_quantity),
      common_unit_ex: Ingredient.common_unit_ex,
      partOfTheDay: selectedPart,
      selectedUnit: Ingredient.ingredientUnits.find(ingredient => ingredient.isSelected === true),
      calorie: Ingredient.calorie,
      protein: Ingredient.protein,
      carb: Ingredient.carb,
      fat: Ingredient.fat,
      glychemicIndex: Ingredient.glycemicIndex,
      currentCalorie: parseInt(event.target.parentElement.parentElement.parentElement.querySelector("#result-of-calorie").innerHTML),
      currentProtein: parseInt(event.target.parentElement.parentElement.parentElement.querySelector("#result-of-protein").innerHTML),
      currentCarb: parseInt(event.target.parentElement.parentElement.parentElement.querySelector("#result-of-carb").innerHTML),
      currentFat: parseInt(event.target.parentElement.parentElement.parentElement.querySelector("#result-of-fat").innerHTML),
      diaryRefId: parseInt(event.target.parentElement.parentElement.parentElement.parentElement.parentElement.dataset.diaryid)
    }



    if (isIngredientForUpdate) {
      let id = event.target.dataset.id;
      let date = event.target.parentElement.parentElement.parentElement.parentElement.parentElement.dataset.date

      fetchIngredient(`/api/ingredient-update/${id}`, newIngredient, date)
      localStorage.removeItem('quantity');
      return;
    }
    fetchIngredient("/api/ingredient-new", newIngredient, date);
    localStorage.removeItem('quantity');
  })


  if (DeleteBtn) {
    DeleteBtn.addEventListener('click', (event) => {
      let date = event.target.parentElement.parentElement.parentElement.parentElement.parentElement.dataset.date
      let id = event.target.dataset.id;


      fetch(`/api/ingredient-delete/${id}`)
        .then(res => res.json())
        .then(data => {
          let state = data.state;
          if (state) {
            window.location.href = `/diary/currentDiary?date=${date}`
            localStorage.removeItem('quantity');
          }
        });

    })
  }
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
  event.preventDefault();
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
  renderDiaryModal();
}

// -----------------TEMPLATE SECTION--------------------------------------------------------------------->

// UnitButtons kigenerálása
const generateUnitTemplate = (Units) => {
  let unitTemplate = "";

  Units.forEach((unit) => {
    unitTemplate += `<button data-index="${unit.index
      }" class="units btn btb-lg btn-outline-dark btn-md m-1 ${unit.isSelected ? "active" : ""
      }">${unit.unitName}</button>`;
  });

  return unitTemplate;
};


