const addIngredientCon = document.querySelector(".add-ingredient-container");
const noAllergen = document.getElementById("no-allergen");
const allergensCon = document.querySelector(".allergen-container");
const allergenValueForUpdate = document.querySelectorAll(".allergen_update_value");

let isNoAllergenBtnActive = false;


const ingredientConCancel = document.querySelector(".add-ingredient-container-cancel");
const allergens = [
  {
    allergenName: "Glutén",
    allergenId: 1,
    isSelected: false,
  },
  {
    allergenName: "Rákfélék",
    allergenId: 2,
    isSelected: false,
  },
  {
    allergenName: "Tojás",
    allergenId: 3,
    isSelected: false,
  },
  {
    allergenName: "Halak",
    allergenId: 4,
    isSelected: false,
  },
  {
    allergenName: "Földimogyoró",
    allergenId: 5,
    isSelected: false,
  },
  {
    allergenName: "Szójabab",
    allergenId: 6,
    isSelected: false,
  },
  {
    allergenName: "Tej",
    allergenId: 7,
    isSelected: false,
  },
  {
    allergenName: "Diófélék",
    allergenId: 8,
    isSelected: false,
  },
  {
    allergenName: "Zeller",
    allergenId: 9,
    isSelected: false,
  },
  {
    allergenName: "Mustár",
    allergenId: 10,
    isSelected: false,
  },
  {
    allergenName: " Szezámmag és abból készült termékek",
    allergenId: 11,
    isSelected: false,
  },
  {
    allergenName: "Kén-dioxid és SO2 -ben kifejezett szulfitok",
    allergenId: 12,
    isSelected: false,
  },
  {
    allergenName: "Csillagfürt és abból készült termékek",
    allergenId: 13,
    isSelected: false,
  },
  {
    allergenName: "Puhatestűek",
    allergenId: 14,
    isSelected: false,
  },
];

let selectedAllergens = [];

window.onload = () => {
  renderAllergenButtons();
};



function renderAllergenButtons() {
  let template = ``;
  allergens.forEach((allergen) => {
    template += `
    <button class="btn  ${
      allergen["isSelected"] ? "btn-warning text-light" : "btn-outline-dark"
    } mt-2 allergen-button" data-id="${allergen["allergenId"]}">${
      allergen["allergenName"]
    }</button>
    `;
  });

  if(allergensCon) {
    allergensCon.innerHTML = template;

  }
  const allergenBtn = document.querySelectorAll(".allergen-button");

  allergenBtn.forEach((btn) => {
    btn.addEventListener("click", (event) => {
      event.preventDefault();

      let newAllergen = {
        allergenName: event.target.textContent,
        allergenId: event.target.dataset.id,
      };

      let index = allergens.findIndex(
        (item) => item.allergenId === parseInt(newAllergen.allergenId)
      );
      allergens[index].isSelected = !allergens[index].isSelected;
      if (allergens[index].isSelected === true) {
        selectedAllergens.push(allergens[index]);
      } else {
        let selectedAllergenIndex = selectedAllergens.findIndex(
          (selectedAllergen) =>
            selectedAllergen.allergenId === allergens[index].allergenId
        );
        selectedAllergens.splice(selectedAllergenIndex, 1);
      }

      localStorage.setItem("allergens", JSON.stringify(selectedAllergens));

      document.getElementById("allergen-input").value =
        localStorage.getItem("allergens");

      renderAllergenButtons();
    });
  });
}

if (noAllergen) {
  noAllergen.addEventListener("click", function (event) {
    event.preventDefault();
    isNoAllergenBtnActive = !isNoAllergenBtnActive;
    event.target.classList.toggle("btn-warning");
    event.target.classList.toggle("text-light");

    allergensCon.classList.toggle("inactive");

    if (isNoAllergenBtnActive) {
      let noAllergen = [
        {
          allergenName: "Nincs Allergén",
          allergenId: 0,
        },
      ];
      localStorage.removeItem("allergens");
      localStorage.setItem("allergens", JSON.stringify(noAllergen));

      document.getElementById("allergen-input").value =
        localStorage.getItem("allergens");
    } else {
      localStorage.setItem("allergens", JSON.stringify(selectedAllergens));
    }
  });
}

function sendIngredient(event) {
  event.preventDefault();
  let isRecommended = event.target.elements.isRecommended.checked;
  let glychemicIndex = event.target.elements.glychemicIndex;
  let allergens = event.target.elements.allergens;
  let allergenInput = document.getElementById("allergen-input");
  let glycemicAlert = document.getElementById("glycemic-alert");
  let allergensAlert = document.getElementById("allergens-alert");

  // Meg kell oldani hogy ha ajánlva van a közösbe akkor az allegenek és a glikémiás kötelező legyen!
  let newIngredient = {
    ingredientName: event.target.elements.ingredientName.value,
    ingredientCategorie: event.target.elements.ingredientCategorie.value,
    unit: event.target.elements.unit.value,
    calorie: event.target.elements.calorie.value,
    common_unit: event.target.elements.common_unit.value,
    common_unit_quantity: event.target.elements.common_unit_quantity.value,
    protein: event.target.elements.protein.value,
    carb: event.target.elements.carb.value,
    fat: event.target.elements.fat.value,
    glychemicIndex: glychemicIndex.value,
    allergens: allergens.value,
    isRecommended: isRecommended ? "on" : "",
  };

  if (
    (isRecommended && allergenInput.value === "") ||
    allergenInput.value === "[]" ||
    (isRecommended && glychemicIndex.value === "")
  ) {
    if (glychemicIndex.value === "") {
      glycemicAlert.innerHTML =
        "Közösségbe való ajánlás esetén kitöltése kötelező!";
      return;
    } else {
      glycemicAlert.innerHTML = "";
    }
    if (allergenInput.value === "" || allergenInput.value === "[]") {
      allergensAlert.innerHTML =
        "Közösségbe való ajánlás esetén kitöltése kötelező!";
    } else {
      allergensAlert.innerHTML = "";
    }
    return;
  }

  fetch("/ingredient/new", {
    method: "POST",
    body: JSON.stringify(newIngredient),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.state) {
        window.location.href = "/ingredients?isSuccess=1";
      }
    });
}
