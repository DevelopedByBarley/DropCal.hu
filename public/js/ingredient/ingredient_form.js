const addIngredientCon = document.querySelector(".add-ingredient-container");
const noAllergen = document.getElementById("no-allergen");
const allergensCon = document.querySelector(".allergen-container");
let isNoAllergenBtnActive = false;
const ingredientConCancel = document.querySelector(".add-ingredient-container-cancel");
const allergensForUpdate = document.querySelectorAll(".allergen_update_value");
let selectedAllergens = [];
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


window.onload = () => {
  localStorage.setItem("allergens", JSON.stringify([]));
}


getValuesOfAllergensForUpdate();
render();
checkNoAllergenIsActive();




// Render metódus
function render() {


  // Rendereljük az allergenek gombjait
  renderButtons(allergens);

  // Kiszedjük a kirednerelt gombokat
  const allergenBtn = document.querySelectorAll(".allergen-button");


  // Foreachelünk rajta
  allergenBtn.forEach((btn) => {
    // Gombonyomásra reagálunk
    btn.addEventListener("click", (event) => {
      event.preventDefault();

      // Létrehozzuk az új allergen objektumot
      let newAllergen = {
        allergenName: event.target.textContent,
        allergenId: event.target.dataset.id,
      };

      // Megkeressük a foreachelt allergénBtn-ek és az aktuális lenyomott gomb indexét
      let index = allergens.findIndex(
        (item) => item.allergenId === parseInt(newAllergen.allergenId)
      );


      //Az index segitségével pedig átállitjuk az isSelected értéket az allergens tömbben, ezáltal a gomb kiválasztódik renderelés közben
      allergens[index].isSelected = !allergens[index].isSelected;

      //Amikor az allergens indexedik eleme true
      if (allergens[index].isSelected === true) {
        //Hozzá pusholjuk a selectedAllergens tömbhöz
        selectedAllergens.push(allergens[index]);
      } else {
        //Minden más esetben pedig megkeressük és töröljük a selectedAllergens-ből
        let selectedAllergenIndex = selectedAllergens.findIndex(
          (selectedAllergen) =>
            selectedAllergen.allergenId === allergens[index].allergenId
        );
        selectedAllergens.splice(selectedAllergenIndex, 1);
      }

      // Majd beállitjuk az selectedAllergens értékét az "allergens" localstorage-be
      localStorage.setItem("allergens", JSON.stringify(selectedAllergens));

      // És beállitjuk ezt a hidden inputnak értéknek, hogy el tudjuk küldeni a backendnek
      document.getElementById("allergen-input").value =
        localStorage.getItem("allergens");

      render();
    });
  });
}


function checkNoAllergenIsActive() {
  let allergens = JSON.parse(localStorage.getItem("allergens"));
  if (allergens) {
    let isNoAllergen = allergens.find(allergen => allergen.allergenId === 0);
    if (isNoAllergen) {
      allergensCon.classList.add("inactive");
      isNoAllergenBtnActive = true;
      let noAllergen = document.getElementById("no-allergen");
      noAllergen.classList.add("btn-warning")
      noAllergen.classList.add("text-light")

    }
  }
}

// Az a funkcionalitás , hogy rá kattintottunk-e a "Nincs Allergén gombra vagy sem"
if (noAllergen) {
  checkNoAllergenIsActive();
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
      let index = selectedAllergens.findIndex(allergen => allergen.allergenId === 0);
      selectedAllergens.splice(index, 1);
      localStorage.setItem("allergens", JSON.stringify(selectedAllergens));
    }
  });
}

// "/ingredient/update/$ingredient[ingredientId]" : "/ingredient/new"

// Új étel küldése
function sendIngredient(event) {
  event.preventDefault();
  let allergensData = JSON.parse(localStorage.getItem("allergens"));
  let isAllergensEmpty = allergensData.length < 1;

  let isRecommended = event.target.elements.isRecommended.checked;
  let glychemicIndex = event.target.elements.glychemicIndex;
  let allergens = event.target.elements.allergens;
  let allergenInput = document.getElementById("allergen-input");
  let glycemicAlert = document.getElementById("glycemic-alert");
  let allergensAlert = document.getElementById("allergens-alert");
  let isIngredientForUpdate = event.target.dataset.exist;
  let ingredientId = event.target.dataset.id;





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
    allergens: isAllergensEmpty ? JSON.stringify([]) : allergens.value,
    isRecommended: isRecommended ? "on" : "",
  };





  // Az a szekció, hogy ha be van kapcsolva az isRecommended és a glikémiás index vagy az allergének valamelyike nincs bepipálva, ne engedje elküldeni a formot

  if ((isRecommended && allergenInput.value === "") || (isRecommended && glychemicIndex.value === "") || (isRecommended && isAllergensEmpty)) {


    if (glychemicIndex.value === "") {
      glycemicAlert.innerHTML =
        "Közösségbe való ajánlás esetén kitöltése kötelező!";
      return;
    } else {
      glycemicAlert.innerHTML = "";
    }
    if (allergenInput.value === "" || isAllergensEmpty) {
      allergensAlert.innerHTML =
        "Közösségbe való ajánlás esetén kitöltése kötelező!";
    } else {
      allergensAlert.innerHTML = "";
    }
    return;
  }


  // Form elküldése
  if (!isIngredientForUpdate) {
    fetchIngredientData("/ingredient/new", newIngredient);
    localStorage.removeItem("allergens");
    return;
  }

  fetchIngredientData(`/ingredient/update/${ingredientId}`, newIngredient)
  localStorage.removeItem("allergens");
}

function fetchIngredientData(url, body) {
  fetch(url, {
    method: "POST",
    body: JSON.stringify(body),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.state) {
        window.location.href = "/ingredients";
      }
    });
}

// Kikérjük az összes allergen update esetén , hogy ki tudjuk velük renderelni a gombokat 
function getValuesOfAllergensForUpdate() {
  if (allergensForUpdate.length !== 0) {
    allergensForUpdate.forEach((item) => {
      let selectedAllergen = {
        allergenName: item.dataset.name,
        allergenId: parseInt(item.dataset.id),
        isSelected: true
      }


      selectedAllergens.push(selectedAllergen);
      localStorage.setItem("allergens", JSON.stringify(selectedAllergens));
      document.getElementById("allergen-input").value =
        localStorage.getItem("allergens");

      for (let i = 0; i < allergens.length; i++) {
        for (let j = 0; j < selectedAllergens.length; j++) {
          if (allergens[i].allergenId === selectedAllergens[j].allergenId) {
            // ha megtaláljuk a megfelelő allergén objektumot, akkor kicseréljük az isSelected értéket
            allergens[i].isSelected = true;
            break; // kilépünk a belső ciklusból, mert már találtunk egyezést
          }
        }
      }
    })


  }
}


// Gombok kirenderelése
function renderButtons(allergens) {
  let template = ``;

  allergens.forEach((allergen) => {
    template += `
    <button class="btn  ${allergen["isSelected"] ? "btn-warning text-light" : "btn-outline-dark"
      } mt-2 allergen-button" data-id="${allergen["allergenId"]}">${allergen["allergenName"]
      }</button>
    `;
  });

  if (allergensCon) {
    allergensCon.innerHTML = template;
  }
}

const commonUnitCheck = document.getElementById('common_unit_check');
const commonUnit = document.getElementById('common_unit');
const commonUnitQuantity = document.getElementById('common_unit_quantity');


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