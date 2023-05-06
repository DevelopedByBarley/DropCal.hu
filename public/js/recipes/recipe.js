
const SearchRecipeIngredient = document.getElementById("search-recipe-ingredient");
const SearchRecipeResultContainer = document.getElementById("search-recipe-result-container");


function searchRecipeIngredient(event) {
    event.preventDefault();
    let name = SearchRecipeIngredient.value;
    if (name.length >= 2) {
        fetch(`/api/search/${name}`)
            .then((res) => res.json())
            .then((data) => {
                searchResult = data["ingredients"]
                numberOfPage = (data["number_of_page"] - 1);
                renderSearchRecipeResult();
            });
    } else {
        clearRecipeContainer(name);
    }
}

function renderSearchRecipeResult() {
    let searchRecipeResultTemplate = ``;
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

    if (searchResult.length > 0) {
        searchRecipeResultTemplate += PaginationTemplate;
    }

    searchResult.forEach((ingredient) => {
        searchRecipeResultTemplate += `
      <li class="list-group-item ingredient-item w-100" data-id="${ingredient.ingredientId}" style="cursor:pointer;">${ingredient.ingredientName}</li>
        `;
    });
    SearchRecipeResultContainer.innerHTML = searchRecipeResultTemplate;

    const PrevButton = document.getElementById('prev-button');
    const NextButton = document.getElementById('next-button');

    PrevButton.addEventListener('click', (event) => prevRecipe(event))

    NextButton.addEventListener('click', (event) => nextRecipe(event))
}











function clearRecipeContainer(name) {
    if (name.length < 3) {
        searchResult = [];
        SearchRecipeResultContainer.innerHTML = "";
    }
}






function prevRecipe(event) {
    event.preventDefault();
    let name = SearchRecipeIngredient.value
    if (pageCounter > 1) {
        pageCounter--;
        localStorage.setItem("page-counter", pageCounter)
    }

    fetch(`/api/search/${name}?page=${pageCounter}`)
        .then((res) => res.json())
        .then((data) => {
            searchResult = data["ingredients"]
            numberOfPage = (data["number_of_page"] - 1);
            renderSearchRecipeResult();
        });
}





function nextRecipe(event) {
    event.preventDefault();
    let name = SearchRecipeIngredient.value;
    if (pageCounter <= numberOfPage) {
        pageCounter++;
        localStorage.setItem("page-counter", pageCounter)
    }
    fetch(`/api/search/${name}?page=${pageCounter}`)
        .then((res) => res.json())
        .then((data) => {
            searchResult = data["ingredients"]
            numberOfPage = (data["number_of_page"] - 1);
            renderSearchRecipeResult();
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
