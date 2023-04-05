//Home.php
const diaryToggle = document.getElementById("diary-ingredients-toggle");
const ingredientsToggle = document.getElementById("ingredients-toggle");
const cancelIngredients = document.getElementById("cancel-ingredients");

if (diaryToggle) {
  diaryToggle.addEventListener("click", (event) => {
    ingredientsToggle.classList.add("active");
  });
}

if (cancelIngredients) {
  cancelIngredients.addEventListener("click", (event) => {
    ingredientsToggle.classList.remove("active");
  });
}

//IngredientToggle.php
const searchBtn = document.getElementById("search-ingredient");
if (searchBtn) {
  searchBtn.addEventListener("click", (event) => {
    let name = event.target.parentElement.parentElement.childNodes[1].value;
    if (name.length >= 2) {
      fetch(`/api/search/${name}`)
        .then((res) => res.json())
        .then((data) => console.log(data));
    }
  });
}
function searchIngredients(event) {
  const name = event.target.value;
  let template = ``;
  if (event.target.value.length >= 3 && event.target.value !== 0) {
    fetch(`/api/search/${name}`)
      .then((res) => res.json())
      .then((ingredients) => {
        for (let i = 0; i < ingredients.length; i++) {
          console.log(ingredients[i]);
          template += `
            <li class="list-group-item searched-item d-flex justify-content-between" style="cursor: pointer" data-id=${ingredients[i].ingredientId}>
            ${ingredients[i].ingredientName} <span>${ingredients[i].calorie}kcal</span>
            </li>
              `;
        }

        document.getElementById("search-result-container").innerHTML = template;
        let searchedItems = document.querySelectorAll(".searched-item");
        searchedItems.forEach((item) => {
          item.addEventListener("click", (event) => {
            let singleIngredientForm = document.getElementById(
              "single-ingredient-form"
            );
            let id = event.target.dataset.id;
            let template = ``;
            fetch(`/api/ingredient-single/${id}`)
              .then((res) => res.json())
              .then((ingredient) => {
                console.log(ingredient);
                template += `
                  <div class="close-ingredient-single bg-light" id="close-ingredient-single">
                    <button class="btn ">X</button>
                  </div>
                      <div class="row">
                        <div class="col-12">
                          <h1>Hello!</h1>
                        </div>
                      </div>
                  `;

                singleIngredientForm.innerHTML = template;
                singleIngredientForm.classList.add("active");
                document
                  .getElementById("close-ingredient-single")
                  .addEventListener("click", () => {
                    singleIngredientForm.classList.remove("active");
                  });
              });
          });
        });
      });
  } else {
    document.getElementById("search-result-container").innerHTML = "";
  }
}
