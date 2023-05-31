let pageCounter = localStorage.getItem("page-counter") ? localStorage.getItem("page-counter") : 1;
const IngredientInput = document.getElementById("ingredients");
const MacrosInput = document.getElementById("macros-input");
const AllergensInput = document.getElementById("allergens-input")
const RecipeName = localStorage.getItem("recipeName") ? JSON.parse(localStorage.getItem("recipeName")) : '';
const SearchBoxContainer = document.getElementById("search-box-container");
const RecipeForUpdate = document.getElementById("recipe-for-update");
let recipeIngredientState = localStorage.getItem("recipeIngredientState") ? JSON.parse(localStorage.getItem("recipeIngredientState")) : [];

const commonUnitCheck = document.getElementById('common_unit_check');
const commonUnit = document.getElementById('common_unit');
const commonUnitQuantity = document.getElementById('common_unit_quantity');

console.log(commonUnit);

const StepButton = document.getElementById("step");
const StepsContainer = document.getElementById("steps-container");
let stepState = localStorage.getItem("stepState") ? JSON.parse(localStorage.getItem("stepState")) : [
    {
        id: generateUUID(),
        content: ""
    }
];


commonUnitCheck.addEventListener('change', function () {
  checkIsCommonUnitExist();
});

checkIsCommonUnitExist();

function checkIsCommonUnitExist() {
  if (commonUnitCheck.checked) {
    commonUnit.disabled = false;
    commonUnitQuantity.disabled = false;
  } else {
    commonUnit.disabled = true;
    commonUnitQuantity.disabled = true;
    commonUnit.value = ''; // Mező kiürítése
    commonUnitQuantity.value = ''; // Mező kiürítése
  }
}


if (RecipeForUpdate) {
    let data = JSON.parse(RecipeForUpdate.dataset.update);
    console.log(data);
    recipeIngredientState = data.ingredients;
    stepState = data.steps;
}

// Oldal betöltődéskor feltöltjük a state-et
window.onload = () => {

    localStorage.setItem("recipeIngredientState", JSON.stringify(recipeIngredientState))
    localStorage.setItem("recipeName", JSON.stringify(RecipeName))
    localStorage.removeItem("page-counter")
    renderIngredientList()
    renderSummaries();
    renderAllergens();
    renderSummaryOfGlycemicIndex();
    IngredientInput.value = localStorage.getItem("recipeIngredientState")
    RecipeName !== "" ? document.getElementById("recipe-name").value = RecipeName : null;
}



function showSearchBox() {
    SearchBoxContainer.innerHTML = `
    <div class="form-outline">
        <div class="mb-1">
            <label for="recipient-name" class="col-form-label mb-2 p-2"><b>Keresés</b></label>
            <input type="text" class="form-control" id="search-recipe-ingredient" placeholder="Hozzávaló keresése">
        </div>
        <div id="search-recipe-result-container"></div>
    </div>
    `

    const SearchRecipeResultContainer = document.getElementById("search-recipe-result-container"); // Illetve a keresésből való visszatéréseknek a konténere
    const SearchBox = document.getElementById("search-recipe-ingredient");

    SearchBox.addEventListener('input', (event) => {
        searchRecipeIngredient(event, SearchRecipeResultContainer)
    })

}


function setRecipeName(event) {
    localStorage.setItem("recipeName", JSON.stringify(event.target.value))
}

function renderIngredientList() {
    let recipeListTemplate = ``;

    recipeIngredientState.forEach((ingredient) => {
        recipeListTemplate += `
            <div class="row border p-2">
                <div class="col-5">${ingredient.ingredientName}</div>
                <div class="col-5"><b>${ingredient.currentCalorie}</b> kCal</div>
                <div class="col-2 d-flex align-items-center justify-content-end">
                    <button class="btn btn-warning text-light btn-sm update-ingredient m-1" data-id="${ingredient.id}">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    <button class="btn btn-danger text-light btn-sm delete-ingredient m-1" data-id="${ingredient.id}">
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
    renderSummaries();
    renderAllergens();
    renderIngredientList();
    renderSummaryOfGlycemicIndex();
    IngredientInput.value = localStorage.getItem("recipeIngredientState")
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
                    <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close" ></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div id="recipe-data-container">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submit-recipe-ingredient" data-bs-dismiss="modal" class="btn ${isIngredientForUpdate ? 'btn-warning text-light' : 'btn btn-primary'}">${isIngredientForUpdate ? 'Frissít' : 'Hozzáad'}</button> 
                </div>
            </div>
        </div>
    </div>
    `

    document.getElementById("modal-container").innerHTML = modalTemplate; // Modal beillesztése a helyére

    const RecipeDataContainer = document.getElementById("recipe-data-container")
    const SubmitRecipeIngredient = document.getElementById("submit-recipe-ingredient")
    const CloseModal = document.querySelectorAll(".close-modal");

    CloseModal.forEach((close) => {
        close.addEventListener('click', () => {
            localStorage.removeItem("ingredientForUpdateId");
            SearchBoxContainer.innerHTML = `
            <div class="form-outline">
                <button type="button" class="btn btn-primary" onclick="showSearchBox()">Összetevő hozzáadása</button>
            </div>
            `
        })
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
                numberOfPage = data["number_of_page"]; // És amit a backend kiszámított nekünk lapozó oldal szám értékével
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
        <li class="page-item"><a class="page-link">${pageCounter === 0 ? 1 : pageCounter} / ${numberOfPage === 0 ? 1 : numberOfPage}</a></li>
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
    if (SearchRecipeResultContainer) {
        SearchRecipeResultContainer.innerHTML = searchRecipeResultTemplate;
    }

    // Ki szedjük a lapozó gombjait a dom-ból
    const PrevButton = document.getElementById('prev-button');
    const NextButton = document.getElementById('next-button');

    // Majd ellátjuk a hozzájuk kellő metódusokkal, ezeknek szintén átadjuk a keresőből kiszedett értéket és azt a containert ahova majd beillesztjük azokat 
    if (PrevButton) PrevButton.addEventListener('click', () => prevRecipe(name, SearchRecipeResultContainer, isIngredientForUpdate))

    if (NextButton) NextButton.addEventListener('click', () => nextRecipe(name, SearchRecipeResultContainer, isIngredientForUpdate))


    // Kiszedjük az összes keresőből visszatért hozzávaló elemet,
    let ingredients = document.querySelectorAll('.ingredient-item');
    // És meghivjuk azt a metódust ami majd végig iterál az elemeken és gombnyomásra ki keresi az adott id-ú elemet
    searchIngredientData(ingredients, isIngredientForUpdate, SearchRecipeResultContainer);
}

// Elindul a renderelés ami majd ki rendereli a keresésből visszatérő hozzávaló elemeket
function searchIngredientData(ingredients, isIngredientForUpdate, SearchRecipeResultContainer) {// Ki szedjük a containert amibe szeretnénk ezeket az elemeket bele helyezni

    ingredients.forEach((ingredient) => { // Végig iterálunk ezeken az elemeket
        ingredient.addEventListener('click', async (event) => {
            let id = event.target.dataset.id; // Ki szedjük az ID-ját

            let ingredient = await getDiaryIngredientById(`/api/ingredient-single/${id}`); // És id alapján backendtől ki kérjük magát a hozzávalót

            if (ingredient) { // És ha ez létezik
                renderModal()
                const RecipeDataContainer = document.getElementById("recipe-data-container");

                renderIngredientDataTemplate(RecipeDataContainer, ingredient, isIngredientForUpdate);
                SearchBoxContainer.innerHTML = "";
                /**
                 * 
                 * renderIngredientDataTemplate(RecipeDataContainer, ingredient, isIngredientForUpdate);  // Akkor ki rendereljük az adatokat módosító formot
                SearchRecipeResultContainer.innerHTML = "";
                let SearchRecipeIngredientContainer = SearchRecipeIngredient.parentElement;
                SearchRecipeIngredientContainer.innerHTML = `<div class="d-flex align-items-center justify-content-center">
                    <b><h1 class="display-4 text-info">${ingredient.ingredientName}</h1></b>
                </div>`
                 */
            }
        })
    })

}


// Ki rendereljük az adatokat módosító formot
function renderIngredientDataTemplate(RecipeDataContainer, ingredient, isIngredientForUpdate) {
    let ingredientUnitTemplate = renderIngredientUnits(ingredient.ingredientUnits);
    let ingredientDataTemplate = `
    <div class="mt-5 p-1">
        <div>
            <h1 class="display-4 mb-5 text-center">${ingredient.ingredientName}</h1>
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Mennyiség</label>
            <input type="number" min='1' class="form-control" id="quantity" value="${ingredient.unit_quantity}" aria-describedby="emailHelp">
        </div>
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

    Quantity.addEventListener('input', (event) => {
        if (event.target.value < 1) {
            SubmitRecipeIngredient.disabled = true;
        } else {
            SubmitRecipeIngredient.disabled = false;
        }
    })

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
                common_unit: ingredient.common_unit,
                common_unit_ex: ingredient.common_unit_ex,
                selectedUnit: ingredient.ingredientUnits.find(ingredient => ingredient.isSelected === true),
                calorie: ingredient.calorie,
                protein: ingredient.protein,
                carb: ingredient.carb,
                fat: ingredient.fat,
                glycemicIndex: ingredient.glycemicIndex,
                ingredientUnits: ingredient.ingredientUnits,
                currentCalorie: calculateCalorie(Quantity.value, ingredient),
                currentProtein: calculateMacros(Quantity.value, ingredient).protein,
                currentCarb: calculateMacros(Quantity.value, ingredient).carb,
                currentFat: calculateMacros(Quantity.value, ingredient).fat,
                allergens: ingredient.allergens,
            }



            let index = recipeIngredientState.findIndex(ingredient => ingredient.id === id);
            recipeIngredientState[index] = newIngredient;



            localStorage.setItem("recipeIngredientState", JSON.stringify(recipeIngredientState));
            renderIngredientList();
            renderSummaries();
            renderAllergens();
            renderSummaryOfGlycemicIndex();


            IngredientInput.value = localStorage.getItem("recipeIngredientState");

            return;
        })
    } else {
        SubmitRecipeIngredient.disabled = false;
        SubmitRecipeIngredient.addEventListener('click', () => {
            let newIngredient = {
                id: generateUUID(),
                ingredientName: ingredient.ingredientName,
                ingredientCategorie: ingredient.ingredientCategorie,
                unit: ingredient.unit,
                unit_quantity: Quantity.value,
                common_unit: ingredient.common_unit,
                common_unit_ex: ingredient.common_unit_ex,
                common_unit_quantity: ingredient.common_unit_ex,
                selectedUnit: ingredient.ingredientUnits.find(ingredient => ingredient.isSelected === true),
                calorie: ingredient.calorie,
                protein: ingredient.protein,
                carb: ingredient.carb,
                fat: ingredient.fat,
                glycemicIndex: ingredient.glycemicIndex,
                ingredientUnits: ingredient.ingredientUnits,
                currentCalorie: calculateCalorie(Quantity.value, ingredient),
                currentProtein: calculateMacros(Quantity.value, ingredient).protein,
                currentCarb: calculateMacros(Quantity.value, ingredient).carb,
                currentFat: calculateMacros(Quantity.value, ingredient).fat,
                allergens: ingredient.allergens,
            }


            console.log(newIngredient);

            for (let i = 0; i < recipeIngredientState.length; i++) {
                if (recipeIngredientState[i].ingredientName === newIngredient.ingredientName) {
                    SearchBoxContainer.innerHTML = `
                    <div class="form-outline">
                        <button type="button" class="btn btn-primary" onclick="showSearchBox()">Összetevő hozzáadása</button>
                    </div>
                    `
                    return;
                }
            }



            recipeIngredientState.push(newIngredient);


            localStorage.setItem("recipeIngredientState", JSON.stringify(recipeIngredientState))
            renderIngredientList();
            renderSummaries();
            renderAllergens();
            renderSummaryOfGlycemicIndex();


            IngredientInput.value = localStorage.getItem("recipeIngredientState");
            SearchBoxContainer.innerHTML = `
            <div class="form-outline">
                <button type="button" class="btn btn-primary" onclick="showSearchBox()">Összetevő hozzáadása</button>
            </div>
            `
            return;
        })
    }
}

function renderSummaryOfGlycemicIndex() {
    let sumOfGlycemicIndex = 0;
    let counter = 0;

    recipeIngredientState.forEach((ingredient) => {
        let glycemicIndex = ingredient.glycemicIndex;

        if (glycemicIndex !== null) {
            sumOfGlycemicIndex += parseInt(glycemicIndex)
        }
        glycemicIndex && glycemicIndex !== null ? counter += 1 : "";
    })

    const GI = sumOfGlycemicIndex !== 0 && counter !== 0 ? (sumOfGlycemicIndex / counter) : 0;

    let glycemicIndexSummaryInput = document.getElementById("glycemic-index-summary");
    glycemicIndexSummaryInput.value = GI;


    document.getElementById("glycemic-index-container").innerHTML = `<b><h5>${GI}</h5></b>`;
}



function renderSummaries() {


    let sumOfCalorie = 0;
    let sumOfProtein = 0;
    let sumOfCarb = 0;
    let sumOfFat = 0;


    recipeIngredientState.forEach((ingredient) => {
        sumOfCalorie += parseInt(ingredient.currentCalorie);
        sumOfProtein += parseInt(ingredient.currentProtein);
        sumOfCarb += parseInt(ingredient.currentCarb);
        sumOfFat += parseInt(ingredient.currentFat);
    })


    document.getElementById("calorie-container").innerHTML = sumOfCalorie;
    document.getElementById("protein-container").innerHTML = sumOfProtein;
    document.getElementById("carb-container").innerHTML = sumOfCarb;
    document.getElementById("fat-container").innerHTML = sumOfFat;

    let macros = {
        sumOfCalorie: sumOfCalorie,
        sumOfProtein: sumOfProtein,
        sumOfCarb: sumOfCarb,
        sumOfFat: sumOfFat,
    }

    localStorage.setItem("macros", JSON.stringify(macros));

    if (recipeIngredientState.length === 0) {
        localStorage.removeItem("macros");
    }


    MacrosInput.value = localStorage.getItem("macros");
}





function renderAllergens() {
    const allergens = [];

    for (const stateElem of Object.values(recipeIngredientState)) {
        if (stateElem.allergens) {
            for (const allergen of stateElem.allergens) {
                if (allergen.i_allergenNumber !== '0') {
                    let alreadyExists = false;
                    for (const existingAllergen of allergens) {
                        if (JSON.stringify(existingAllergen.i_allergenNumber) === JSON.stringify(allergen.i_allergenNumber)) {
                            alreadyExists = true;
                            break;
                        }
                    }
                    if (!alreadyExists) {
                        allergens.push(allergen);
                        let allergenNumbers = [];
                        for (const allergen of allergens) {
                            allergenNumbers.push(parseInt(allergen.i_allergenNumber));
                        }
                        allergenNumbers.sort(function (a, b) {
                            return a - b;
                        });

                        localStorage.setItem("recipeAllergens", JSON.stringify(allergenNumbers))
                    }
                }
            }
        }
    }

    if (allergens.length !== 0) {
        let allergensTemplate = '';
        let allergenNumbers = [];
        for (const allergen of allergens) {
            allergenNumbers.push(parseInt(allergen.i_allergenNumber));
        }

        allergenNumbers.sort(function (a, b) {
            return a - b;
        });

        allergenNumbers.forEach((num) => {
            allergensTemplate += `
            <span>${num}</span>
        `;
        })

        document.getElementById('recipe-allergens-container').innerHTML = allergensTemplate;
        AllergensInput.value = localStorage.getItem("recipeAllergens");
    } else {
        document.getElementById('recipe-allergens-container').innerHTML = '<span>Nincs allergén</span>';
        localStorage.removeItem("recipeAllergens")
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
        if (SearchRecipeResultContainer) {
            SearchRecipeResultContainer.innerHTML = "";
        }
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
            numberOfPage = data["number_of_page"];
            renderSearchRecipeResult(name, SearchRecipeResultContainer, isIngredientForUpdate);
        });
}

function nextRecipe(name, SearchRecipeResultContainer, isIngredientForUpdate) {
    if (pageCounter < numberOfPage) {
        pageCounter++;
        localStorage.setItem("page-counter", pageCounter)
    } else {
        pageCounter = numberOfPage
        renderSearchRecipeResult(name, SearchRecipeResultContainer, isIngredientForUpdate);
        return;
    }
    fetch(`/api/search/${name}?page=${pageCounter}`)
        .then((res) => res.json())
        .then((data) => {
            searchResult = data["ingredients"]
            numberOfPage = data["number_of_page"];
            renderSearchRecipeResult(name, SearchRecipeResultContainer, isIngredientForUpdate);
        });
}







// Kalória kalkulálása
function calculateCalorie(quantity, ingredient) {
    const Units = ingredient.ingredientUnits
    let unit = Units.find(unit => unit.isSelected === true);
    let multiplier = parseInt(unit.multiplier);
    let kCal = parseInt(ingredient.calorie);


    let calculated = ((parseInt(quantity) * multiplier) / 100) * kCal;

    return Math.round(calculated);
}


// Makrók kalulálása
function calculateMacros(quantity, ingredient) {
    const Units = ingredient.ingredientUnits
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




































function renderSteps() {
    let stepsTemplate = ``;

    stepState.forEach((step, index) => {
        stepsTemplate += `
            <div class="row mb-5">
                <div class="col-12 col-md-10">
                    <div class="form-outline">
                        <label for="exampleFormControlTextarea1" class="form-label"><b class="text-primary">${index + 1}</b>. lépés</label>
                        <textarea class="form-control step-content" id="form4Example3" rows="4" name="steps[]" data-id="${step.id}" required>${stepState[index].content.trim()}</textarea>
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
    let stepContents = document.querySelectorAll('.step-content');

    stepContents.forEach((step) => {
        step.addEventListener('input', (event) => {
            let id = event.target.dataset.id;
            let index = stepState.findIndex(step => step.id === id);

            stepState[index].content = event.target.value;

            localStorage.setItem("stepState", JSON.stringify(stepState));

        })
    })
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
    localStorage.setItem("stepState", JSON.stringify(stepState));

    renderSteps();
}

if (StepButton) {
    StepButton.addEventListener('click', (event) => {
        event.preventDefault();

        let newStep = {
            id: generateUUID(),
            content: ""
        }

        stepState.push(newStep);
        localStorage.setItem("stepState", JSON.stringify(stepState));
        renderSteps()
    })
}

renderSteps();










commonUnitCheck.addEventListener('change', function () {
    checkIsCommonUnitExist();
  });
  
  checkIsCommonUnitExist();


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
