
let recipeIngredientState = localStorage.getItem("recipeIngredientState") ? JSON.parse(localStorage.getItem("recipeIngredientState")) : [];

// Oldal betöltődéskor feltöltjük a state-et
window.onload = () => {
    localStorage.setItem("recipeIngredientState", JSON.stringify(recipeIngredientState))
    localStorage.removeItem("page-counter")
    renderIngredientList()
}


function renderIngredientList() {
    let recipeListTemplate = ``;

    recipeIngredientState.forEach((ingredient) => {
        recipeListTemplate += `
            <div class="row border p-2">
                <div class="col-5">${ingredient.ingredientName}</div>
                <div class="col-5"><b>${ingredient.calorie}</b> kCal</div>
                <div class="col-2 d-flex align-items-center justify-content-end">
                    <button class="btn btn-warning text-light btn-sm update-ingredient" data-id="${ingredient.id}">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    <button class="btn btn-danger text-light btn-sm delete-ingredient" data-id="${ingredient.id}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });

    document.getElementById("ingredient-list-container").innerHTML = recipeListTemplate;


    const deleteIngredientBtns = document.querySelectorAll(".delete-ingredient");
    deleteIngredientBtns.forEach(btn => btn.addEventListener("click", (event) => deleteIngredient(event)));

    const updateIngredientBtns = document.querySelectorAll(".update-ingredient");
    updateIngredientBtns.forEach(btn => btn.addEventListener("click", (event) => updateIngredient(event)));
}



function deleteIngredient(event) {
    event.preventDefault()
    let id = event.currentTarget.dataset.id;

    let indexForDelete = recipeIngredientState.findIndex(ingredient => ingredient.id === id);
    recipeIngredientState.splice(indexForDelete, 1);

    localStorage.setItem("recipeIngredientState", JSON.stringify(recipeIngredientState));
    renderIngredientList();
}

function updateIngredient(event) {
    event.preventDefault()
    let id = event.currentTarget.dataset.id;

    renderModal(id);
}



// Modal kirajzolása és megjelenítése
function renderModal(idForUpdate = null) {
    if (idForUpdate) {
        let ingredient = recipeIngredientState.find(ingredient => ingredient.id === idForUpdate);

        renderModalTemplate(ingredient); // Megjelenik a modal, paraméterként meg kell adni, hogy van-e frissítendő elem
        const modal = new bootstrap.Modal(document.getElementById('exampleModal'));
        modal.show();
        return;
    }
    renderModalTemplate(); // Megjelenik a modal, paraméterként meg kell adni, hogy van-e frissítendő elem
    const modal = new bootstrap.Modal(document.getElementById('exampleModal'));
    modal.show();
}

// Modal kirajzolása

function renderModalTemplate(ingredient = null) { // Ha a modalt renderelő functionnek dobunk be értéket és nem null akkor a modalt updateléshez hozzuk létre
    let isIngredientForUpdate = ingredient ? true : false;
    isIngredientForUpdate ? renderIngredientUnits(ingredient.ingredientUnits) : [];
    isIngredientForUpdate ? localStorage.setItem("ingredientForUpdateId", JSON.stringify(ingredient.id)) : '';
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
                           
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="close" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submit-recipe-ingredient" data-bs-dismiss="modal" class="btn ${isIngredientForUpdate ? 'btn-warning text-light' : 'btn btn-primary'}">${isIngredientForUpdate ? 'Frissít' : 'Hozzáad'}</button> 
                </div>
            </div>
        </div>
    </div>
    `

    document.getElementById("modal-container").innerHTML = modalTemplate; // Modal beillesztése a helyére

    const SearchRecipeIngredient = document.getElementById("search-recipe-ingredient"); // Kereső mező ki kérése a DOM-ból
    const SearchRecipeResultContainer = document.getElementById("search-recipe-result-container"); // Illetve a keresésből való visszatéréseknek a konténere
    const RecipeDataContainer = document.getElementById("recipe-data-container")
    const SubmitRecipeIngredient = document.getElementById("submit-recipe-ingredient")
    const Close = document.getElementById("close");

    Close.addEventListener('click', () => {
        localStorage.removeItem("ingredientForUpdateId");
    })

    SearchRecipeIngredient.addEventListener('input', (event) => { // Keresőmező inputjába beírjuk a keresni valót
        searchRecipeIngredient(event, SearchRecipeResultContainer, isIngredientForUpdate) // Ami elindítja a kereséső metódust és átadja neki az eventet és a konténert ahova majd bele rakjuk a visszatért elemeket
    })


    if (isIngredientForUpdate) {
        renderIngredientDataTemplate(RecipeDataContainer, ingredient, isIngredientForUpdate)
    } else {
        SubmitRecipeIngredient.disabled = true;
    }

    SubmitRecipeIngredient.addEventListener('click', () => {
        let modal = new bootstrap.Modal(document.getElementById('exampleModal'))
        modal.hide();
    })

}


// Kereső metódus elindul minden gombnyomásra
function searchRecipeIngredient(event, SearchRecipeResultContainer, isIngredientForUpdate) {
    event.preventDefault();
    let name = event.target.value; // Ki szedjük a kereső értékét
    if (name.length >= 2) {
        fetch(`/api/search/${name}`) // Meginditjuk a backend felé a kérést
            .then((res) => res.json())
            .then((data) => {
                searchResult = data["ingredients"] // Vissza térünk a hozzávalókkal 
                numberOfPage = (data["number_of_page"] - 1); // És amit a backend kiszámított nekünk lapozó oldal szám értékével
                renderSearchRecipeResult(name, SearchRecipeResultContainer, isIngredientForUpdate); // Ekkor elindigjuk a visszatért értékek kirenderelését
            });
    } else {
        clearRecipeContainer(name, SearchRecipeResultContainer);
    }
}


// Keresőből visszatért értékek renderelése
function renderSearchRecipeResult(name, SearchRecipeResultContainer, isIngredientForUpdate) {
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
    PrevButton.addEventListener('click', () => prevRecipe(name, SearchRecipeResultContainer, isIngredientForUpdate))

    NextButton.addEventListener('click', () => nextRecipe(name, SearchRecipeResultContainer, isIngredientForUpdate))


    // Kiszedjük az összes keresőből visszatért hozzávaló elemet,
    let ingredients = document.querySelectorAll('.ingredient-item');
    // És meghivjuk azt a metódust ami majd végig iterál az elemeken és gombnyomásra ki keresi az adott id-ú elemet
    searchIngredientData(ingredients, isIngredientForUpdate);
}

// Elindul a renderelés ami majd ki rendereli a keresésből visszatérő hozzávaló elemeket
function searchIngredientData(ingredients, isIngredientForUpdate) {
    const RecipeDataContainer = document.getElementById("recipe-data-container") // Ki szedjük a containert amibe szeretnénk ezeket az elemeket bele helyezni

    ingredients.forEach((ingredient) => { // Végig iterálunk ezeken az elemeket
        ingredient.addEventListener('click', async (event) => {
            let id = event.target.dataset.id; // Ki szedjük az ID-ját

            let ingredient = await getDiaryIngredientById(`/api/ingredient-single/${id}`); // És id alapján backendtől ki kérjük magát a hozzávalót
            const SearchRecipeIngredient = document.getElementById("search-recipe-ingredient");
            SearchRecipeIngredient.value = ingredient.ingredientName;
            if (ingredient) { // És ha ez létezik
                renderIngredientDataTemplate(RecipeDataContainer, ingredient, isIngredientForUpdate);  // Akkor ki rendereljük az adatokat módosító formot
            }
        })
    })

}


// Ki rendereljük az adatokat módosító formot
function renderIngredientDataTemplate(RecipeDataContainer, ingredient, isIngredientForUpdate) {
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

    const SubmitRecipeIngredient = document.getElementById("submit-recipe-ingredient")
    const Units = document.getElementById("units");
    const Quantity = document.getElementById("quantity")

    Units.addEventListener('change', (event) => {
        const selectedIndex = parseInt(event.target.options[event.target.selectedIndex].getAttribute('data-index'));
        for (i = 0; i < ingredient.ingredientUnits.length; i++) {
            if (selectedIndex === i) {
                ingredient.ingredientUnits[i].isSelected = true;
            } else {
                ingredient.ingredientUnits[i].isSelected = false;
            }
        }
    })

    if (isIngredientForUpdate) {
        SubmitRecipeIngredient.addEventListener('click', () => {
            let id = JSON.parse(localStorage.getItem("ingredientForUpdateId"));


            let newIngredient = {
                id: id,
                ingredientName: ingredient.ingredientName,
                unit: ingredient.unit,
                unit_quantity: Quantity.value,
                commonUnit: ingredient.common_unit,
                common_unit_ex: ingredient.common_unit_ex,
                partOfTheDay: 1,
                selectedUnit: ingredient.ingredientUnits.find(ingredient => ingredient.isSelected === true),
                calorie: ingredient.calorie,
                protein: ingredient.protein,
                carb: ingredient.carb,
                fat: ingredient.fat,
                glychemicIndex: ingredient.glycemicIndex,
                ingredientUnits: ingredient.ingredientUnits,
                allergens: ingredient.allergens
            }



            let index = recipeIngredientState.findIndex(ingredient => ingredient.id === id);
            recipeIngredientState[index] = newIngredient;


            localStorage.setItem("recipeIngredientState", JSON.stringify(recipeIngredientState));
            renderIngredientList();
            return;
        })
    } else {
        SubmitRecipeIngredient.disabled = false;
        SubmitRecipeIngredient.addEventListener('click', () => {
            let newIngredient = {
                id: generateUUID(),
                ingredientName: ingredient.ingredientName,
                unit: ingredient.unit,
                unit_quantity: Quantity.value,
                commonUnit: ingredient.common_unit,
                common_unit_ex: ingredient.common_unit_ex,
                partOfTheDay: 1,
                selectedUnit: ingredient.ingredientUnits.find(ingredient => ingredient.isSelected === true),
                calorie: ingredient.calorie,
                protein: ingredient.protein,
                carb: ingredient.carb,
                fat: ingredient.fat,
                glychemicIndex: ingredient.glycemicIndex,
                ingredientUnits: ingredient.ingredientUnits,
                allergens: ingredient.allergens
            }

            recipeIngredientState.push(newIngredient);

            localStorage.setItem("recipeIngredientState", JSON.stringify(recipeIngredientState))
            renderIngredientList()
            return;
        })
    }
}


// Ki rendereljük az egységeket az adatokat módosító formhoz
function renderIngredientUnits(units) {
    let unitTemplate = ``;

    units.forEach((unit) => {
        unitTemplate += `
            <option ${unit.isSelected ? 'selected' : ''} data-index="${unit.index}">${unit.unitName}</option>
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


















function clearRecipeContainer(name, SearchRecipeResultContainer) {
    if (name.length < 3) {
        searchResult = [];
        SearchRecipeResultContainer.innerHTML = "";
    }
}

function prevRecipe(name, SearchRecipeResultContainer, isIngredientForUpdate) {
    if (pageCounter > 1) {
        pageCounter--;
        localStorage.setItem("page-counter", pageCounter)
    }

    fetch(`/api/search/${name}?page=${pageCounter}`)
        .then((res) => res.json())
        .then((data) => {
            searchResult = data["ingredients"]
            numberOfPage = (data["number_of_page"] - 1);
            renderSearchRecipeResult(name, SearchRecipeResultContainer, isIngredientForUpdate);
        });
}

function nextRecipe(name, SearchRecipeResultContainer, isIngredientForUpdate) {
    if (pageCounter <= numberOfPage) {
        pageCounter++;
        localStorage.setItem("page-counter", pageCounter)
    }
    fetch(`/api/search/${name}?page=${pageCounter}`)
        .then((res) => res.json())
        .then((data) => {
            searchResult = data["ingredients"]
            numberOfPage = (data["number_of_page"] - 1);
            renderSearchRecipeResult(name, SearchRecipeResultContainer, isIngredientForUpdate);
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
