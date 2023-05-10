/**
 *      {
        ingredientId: '82',
        ingredientName: 'Paradicsom',
        ingredientCategorie: 'Jégkrém',
        unit: 'g',
        unit_quantity: '100',
        calorie: '22',
        carb: '2',
        fat: '2',
        protein: '2',
        glycemicIndex: '2',
        common_unit: 'Darab',
        common_unit_ex: 'g',
        common_unit_quantity: '2',
        isAccepted: '0',
        isRecommended: '1',
        userRefId: '29',
        ingredientUnits: [
          { index: 0, unitName: 'g', multiplier: 1, isSelected: false },
          { index: 1, unitName: 'dkg', multiplier: 10, isSelected: false },
          { index: 2, unitName: 'kg', multiplier: 1000, isSelected: true },
          { index: 3, unitName: 'Darab', multiplier: 2, common_unit_ex: 'g', isSelected: false }
        ],
        allergens: [
          { i_allergenId: '123', i_allergenNumber: '2', i_allergenName: 'Rákfélék', ingredientRefId: '82' },
          { i_allergenId: '124', i_allergenNumber: '1', i_allergenName: 'Glutén', ingredientRefId: '82' }
        ]
    }
 */

// Recipe state a localstorage-nek
let recipeIngredientState = localStorage.getItem("recipeIngredientState") ? JSON.parse(localStorage.getItem("recipeIngredientState")) : [];

// Oldal betöltődéskor feltöltjük a state-et
window.onload = () => {
    localStorage.setItem("recipeIngredientState", JSON.stringify(recipeIngredientState))
}

// Modal kirajzolása és megjelenítése
function renderModal() {
    renderModalTemplate({
        ingredientId: '82',
        ingredientName: 'Paradicsom',
        ingredientCategorie: 'Jégkrém',
        unit: 'g',
        unit_quantity: '100',
        calorie: '22',
        carb: '2',
        fat: '2',
        protein: '2',
        glycemicIndex: '2',
        common_unit: 'Darab',
        common_unit_ex: 'g',
        common_unit_quantity: '2',
        isAccepted: '0',
        isRecommended: '1',
        userRefId: '29',
        ingredientUnits: [
          { index: 0, unitName: 'g', multiplier: 1, isSelected: false },
          { index: 1, unitName: 'dkg', multiplier: 10, isSelected: false },
          { index: 2, unitName: 'kg', multiplier: 1000, isSelected: true },
          { index: 3, unitName: 'Darab', multiplier: 2, common_unit_ex: 'g', isSelected: false }
        ],
        allergens: [
          { i_allergenId: '123', i_allergenNumber: '2', i_allergenName: 'Rákfélék', ingredientRefId: '82' },
          { i_allergenId: '124', i_allergenNumber: '1', i_allergenName: 'Glutén', ingredientRefId: '82' }
        ]
    }); // Megjelenik a modal, paraméterként meg kell adni, hogy van-e frissítendő elem
    const modal = new bootstrap.Modal(document.getElementById('exampleModal'));
    modal.show();
}

// Modal kirajzolása

function renderModalTemplate(ingredient = null) { // Ha a modalt renderelő functionnek dobunk be értéket és nem null akkor a modalt updateléshez hozzuk létre
    let isIngredientForUpdate = ingredient ? true : false;
    let ingredientUnitTemplate = isIngredientForUpdate ? renderIngredientUnits(ingredient.ingredientUnits) : [];
    let modalTemplate = `
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Összetevő hozzáadása</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Keresés:</label>
                            <input type="text" class="form-control" id="search-recipe-ingredient" value="${isIngredientForUpdate ? ingredient.ingredientName : ''}" oninput="searchRecipeIngredient(event)">
                        </div>
                        <div id="search-recipe-result-container"></div>
                        <div id="recipe-data-container">
                            ${isIngredientForUpdate ? `
                            <div class="mt-5 p-1">
                                <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Mennyiség</label>
                                <input type="number" min='1' class="form-control" id="quantity" value="${ingredient.unit_quantity}" aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Egység</label>
                                <select class="form-control" id="units"> 
                                    ${ingredientUnitTemplate}
                                </select>
                            </div>
                            ` : ``}
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submit-recipe-ingredient" class="btn ${isIngredientForUpdate ? 'btn-warning text-light': 'btn btn-primary'}">${isIngredientForUpdate ? 'Frissít': 'Hozzáad'}</button> 
                </div>
            </div>
        </div>
    </div>
    `

    document.getElementById("modal-container").innerHTML = modalTemplate; // Modal beillesztése a helyére

    const SearchRecipeIngredient = document.getElementById("search-recipe-ingredient"); // Kereső mező ki kérése a DOM-ból
    const SearchRecipeResultContainer = document.getElementById("search-recipe-result-container"); // Illetve a keresésből való visszatéréseknek a konténere

    SearchRecipeIngredient.addEventListener('input', (event) => { // Keresőmező inputjába beírjuk a keresni valót
        searchRecipeIngredient(event, SearchRecipeResultContainer) // Ami elindítja a kereséső metódust és átadja neki az eventet és a konténert ahova majd bele rakjuk a visszatért elemeket
    })
}


// Kereső metódus elindul minden gombnyomásra
function searchRecipeIngredient(event, SearchRecipeResultContainer) {
    event.preventDefault();
    let name = event.target.value; // Ki szedjük a kereső értékét
    if (name.length >= 2) {
        fetch(`/api/search/${name}`) // Meginditjuk a backend felé a kérést
            .then((res) => res.json())
            .then((data) => {
                searchResult = data["ingredients"] // Vissza térünk a hozzávalókkal 
                numberOfPage = (data["number_of_page"] - 1); // És amit a backend kiszámított nekünk lapozó oldal szám értékével
                renderSearchRecipeResult(name, SearchRecipeResultContainer); // Ekkor elindigjuk a visszatért értékek kirenderelését
            });
    } else {
        clearRecipeContainer(name, SearchRecipeResultContainer);
    }
}


// Keresőből visszatért értékek renderelése
function renderSearchRecipeResult(name, SearchRecipeResultContainer) {
    let searchRecipeResultTemplate = ``; //Paginationt hozzá adjuk
    const PaginationTemplate = ` 
    <nav aria-label="Page navigation example">
      <ul class="pagination mt-3 mb-3">
        <li class="page-item">
          <a class="page-link ${pageCounter <= 1 ? 'disabled' : ''}" aria-label="Previous" style="cursor: pointer" id="prev-button">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li class="page-item"><a class="page-link">${pageCounter} / ${(numberOfPage + 1)}</a></li>
        <li class="page-item">
          <a class="page-link ${pageCounter >= numberOfPage ? 'disabled' : ''}" aria-label="Next" style="cursor: pointer" id="next-button">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
  
    `;

    // Kivége ha a visszatért eredmény kissebb mint 1
    if (searchResult.length > 0) {
        searchRecipeResultTemplate += PaginationTemplate;
    }

    // Majd ki rendereljük a visszatért hozzávalók elemeit is
    searchResult.forEach((ingredient) => {
        searchRecipeResultTemplate += `
      <li class="list-group-item ingredient-item w-100" data-id="${ingredient.ingredientId}" style="cursor:pointer;">${ingredient.ingredientName}</li>
        `;
    });

    // Amellyet beillesztünk a megfelelő helyre
    SearchRecipeResultContainer.innerHTML = searchRecipeResultTemplate;

    // Ki szedjük a lapozó gombjait a dom-ból
    const PrevButton = document.getElementById('prev-button');
    const NextButton = document.getElementById('next-button');

    // Majd ellátjuk a hozzájuk kellő metódusokkal, ezeknek szintén átadjuk a keresőből kiszedett értéket és azt a containert ahova majd beillesztjük azokat 
    PrevButton.addEventListener('click', () => prevRecipe(name, SearchRecipeResultContainer))

    NextButton.addEventListener('click', () => nextRecipe(name, SearchRecipeResultContainer))


    // Kiszedjük az összes keresőből visszatért hozzávaló elemet,
    let ingredients = document.querySelectorAll('.ingredient-item');
    // És meghivjuk azt a metódust ami majd végig iterál az elemeken és gombnyomásra ki keresi az adott id-ú elemet
    searchIngredientData(ingredients);
}

// Elindul a renderelés ami majd ki rendereli a keresésből visszatérő hozzávaló elemeket
function searchIngredientData(ingredients) {
    const RecipeDataContainer = document.getElementById("recipe-data-container") // Ki szedjük a containert amibe szeretnénk ezeket az elemeket bele helyezni

    ingredients.forEach((ingredient) => { // Végig iterálunk ezeken az elemeket
        ingredient.addEventListener('click', async (event) => {
            let id = event.target.dataset.id; // Ki szedjük az ID-ját

            let ingredient = await getDiaryIngredientById(`/api/ingredient-single/${id}`); // És id alapján backendtől ki kérjük magát a hozzávalót
            const SearchRecipeIngredient = document.getElementById("search-recipe-ingredient");
            SearchRecipeIngredient.value = ingredient.ingredientName;
            if (ingredient) { // És ha ez létezik
                renderIngredientDataTemplate(RecipeDataContainer, ingredient);  // Akkor ki rendereljük az adatokat módosító formot
            }
        })
    })

}


// Ki rendereljük az adatokat módosító formot
function renderIngredientDataTemplate(RecipeDataContainer, ingredient) {
    let ingredientUnitTemplate = renderIngredientUnits(ingredient.ingredientUnits);
    let ingredientDataTemplate = `
    <div class="mt-5 p-1">
        <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Mennyiség</label>
        <input type="number" min='1' class="form-control" id="quantity" value="${ingredient.unit_quantity}" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Egység</label>
        <select class="form-control" id="units"> 
            ${ingredientUnitTemplate}
        </select>
    </div>
    `

    RecipeDataContainer.innerHTML = ingredientDataTemplate;
}


// Ki rendereljük az egységeket az adatokat módosító formhoz
function renderIngredientUnits(units) {
    let unitTemplate = ``;

    units.forEach((unit) => {
        unitTemplate += `
            <option ${unit.isSelected ? 'selected' : ''}>${unit.unitName}</option>
        `
    })

    return unitTemplate;
}


// Id alapján ki keressük a hozzávalót
async function getDiaryIngredientById(url) {
    const response = await fetch(url);
    const data = await response.json();
    if (data) {
        return data;
    }
}


















function clearRecipeContainer(name, name, SearchRecipeResultContainer) {
    if (name.length < 3) {
        searchResult = [];
        SearchRecipeResultContainer.innerHTML = "";
    }
}

function prevRecipe(name, SearchRecipeResultContainer) {
    if (pageCounter > 1) {
        pageCounter--;
        localStorage.setItem("page-counter", pageCounter)
    }

    fetch(`/api/search/${name}?page=${pageCounter}`)
        .then((res) => res.json())
        .then((data) => {
            searchResult = data["ingredients"]
            numberOfPage = (data["number_of_page"] - 1);
            renderSearchRecipeResult(name, SearchRecipeResultContainer);
        });
}

function nextRecipe(name, SearchRecipeResultContainer) {
    if (pageCounter <= numberOfPage) {
        pageCounter++;
        localStorage.setItem("page-counter", pageCounter)
    }
    fetch(`/api/search/${name}?page=${pageCounter}`)
        .then((res) => res.json())
        .then((data) => {
            searchResult = data["ingredients"]
            numberOfPage = (data["number_of_page"] - 1);
            renderSearchRecipeResult(name, SearchRecipeResultContainer);
        });
}











































// Steps Section

const StepButton = document.getElementById("step");
const StepsContainer = document.getElementById("steps-container");
let stepState = [
    {
        id: generateUUID()
    }
];






function renderSteps() {
    let stepsTemplate = ``;

    stepState.forEach((step, index) => {
        index += 1;
        stepsTemplate += `
            <!-- 2 column grid layout with text inputs for the first and last names -->
            <div class="row mb-5">
                <div class="col-12 col-md-10">
                    <div class="form-outline">
                        <label for="exampleFormControlTextarea1" class="form-label"><b class="text-primary">${index}</b>. lépés</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="steps[]" required></textarea>
                    </div>
                </div>
                <div class="col-12 col-md-2 d-flex align-items-center justify-content-center mt-3">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-danger m-2 delete-step" data-id="${step.id}">Törlés </button>
                    </div>
                </div>
            </div>
        `
    })

    StepsContainer.innerHTML = stepsTemplate;

    let deleteButtons = document.querySelectorAll('.delete-step');

    deleteButtons.forEach((btn) => {
        if (stepState.length > 1) {
            btn.addEventListener('click', onDeleteStep);
        }
    });


}

function onDeleteStep(event) {
    event.preventDefault();
    let id = event.currentTarget.dataset.id;
    let indexForDelete = stepState.findIndex(step => step.id === id);
    stepState.splice(indexForDelete, 1);
    renderSteps();
}

if (StepButton) {
    StepButton.addEventListener('click', (event) => {
        event.preventDefault();

        let newStep = {
            id: generateUUID()
        }

        stepState.push(newStep);

        renderSteps()
    })
}

renderSteps();


// UUID

function generateUUID() {
    let d = new Date().getTime();
    if (typeof performance !== 'undefined' && typeof performance.now === 'function') {
        d += performance.now();
    }
    const uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        const r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    return uuid;
}
