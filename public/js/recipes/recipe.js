
const StepButton = document.getElementById("step");

StepButton.addEventListener('click', (event) => {
    event.preventDefault();
    console.log(event.target);
})

function searchRecipeIngredient(event) {
    event.preventDefault();
    console.log(event.target);
}