


function searchRecipeIngredient(event) {
    event.preventDefault();
    console.log(event.currentTarget);
}

function searchRecipeIngredients() {
    
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
                <div class="col-12 col-md-11">
                    <div class="form-outline">
                        <label for="exampleFormControlTextarea1" class="form-label"><b class="text-primary">${index}</b>. lépés</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="steps[]" required></textarea>
                    </div>
                </div>
                <div class="col-12 col-md-1 d-flex align-items-center justify-content-center mt-3">
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

StepButton.addEventListener('click', (event) => {
    event.preventDefault();

    let newStep = {
        id: generateUUID()
    }

    stepState.push(newStep);

    renderSteps()
})

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
