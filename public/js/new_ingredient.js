const addIngredientFormNav = document.getElementById("add-ingredient-form-nav");
const addIngredientCon = document.querySelector(".add-ingredient-container");
const allergens = [
    {
        allergenName: "Glutén",
        allergenId: 1,
        isSelected: false
    },
    {
        allergenName: "Rákfélék",
        allergenId: 2,
        isSelected: false
    },
    {
        allergenName: "Tojás",
        allergenId: 3,
        isSelected: false
    },
    {
        allergenName: "Halak",
        allergenId: 4,
        isSelected: false
    },
    {
        allergenName: "Földimogyoró",
        allergenId: 5,
        isSelected: false
    },
    {
        allergenName: "Szójabab",
        allergenId: 6,
        isSelected: false
    },
    {
        allergenName: "Tej",
        allergenId: 7,
        isSelected: false
    },
    {
        allergenName: "Diófélék",
        allergenId: 8,
        isSelected: false
    },
    {
        allergenName: "Zeller",
        allergenId: 9,
        isSelected: false
    },
    {
        allergenName: "Mustár",
        allergenId: 10,
        isSelected: false
    },
    {
        allergenName: " Szezámmag és abból készült termékek",
        allergenId: 11,
        isSelected: false
    },
    {
        allergenName: "Kén-dioxid és SO2 -ben kifejezett szulfitok",
        allergenId: 12,
        isSelected: false
    },
    {
        allergenName: "Csillagfürt és abból készült termékek",
        allergenId: 13,
        isSelected: false
    },
    {
        allergenName: "Puhatestűek",
        allergenId: 14,
        isSelected: false
    },
];
let selectedAllergens = [];

window.onload = () => {
    renderAllergenButtons();
}


addIngredientFormNav.addEventListener('click', (event) => {
    addIngredientCon.classList.add("active");
})

document.querySelector('.add-ingredient-container-cancel').addEventListener("click", (event) => {
    addIngredientCon.classList.remove("active");
})



function renderAllergenButtons() {
    let template = ``;
    allergens.forEach((allergen) => {
        template += `
    <button class="btn  ${allergen["isSelected"] ? "btn-warning text-light" : "btn-outline-dark"} mt-2 allergen-button" data-id="${allergen["allergenId"]}">${allergen["allergenName"]}</button>
    `
    })


    document.querySelector('.allergen-container').innerHTML = template;
    const allergenBtn = document.querySelectorAll('.allergen-button')

    allergenBtn.forEach((btn) => {
        btn.addEventListener("click", (event) => {
            event.preventDefault();

            let newAllergen = {
                allergenName: event.target.textContent,
                allergenId: event.target.dataset.id
            }


            let index = allergens.findIndex((item) => item.allergenId === parseInt(newAllergen.allergenId));
            allergens[index].isSelected = !allergens[index].isSelected;
            if (allergens[index].isSelected === true) {
                selectedAllergens.push(allergens[index]);
            } else {
                let selectedAllergenIndex = selectedAllergens.findIndex(selectedAllergen => selectedAllergen.allergenId === allergens[index].allergenId);
                selectedAllergens.splice(selectedAllergenIndex, 1);
            }

            localStorage.setItem("allergens", JSON.stringify(selectedAllergens));

            document.getElementById("allergen-input").value = localStorage.getItem("allergens");

            console.log(document.getElementById("allergen-input").value)

            renderAllergenButtons();
        })
    })
}


function sendIngredient(event) {
    event.preventDefault();
    let isRecommended = event.target.elements.isRecommended.value

 // Meg kell oldani hogy ha ajánlva van a közösbe akkor az allegenek és a glikémiás kötelező legyen!
    let newIngredient = {
        ingredientName: event.target.elements.ingredientName.value,
        ingredientCategorie: event.target.elements.ingredientCategorie.value,
        unit: event.target.elements.unit.value,
        calorie: event.target.elements.calorie.value,
        protein: event.target.elements.protein.value,
        carb: event.target.elements.carb.value,
        c_unit: event.target.elements.c_unit.value,
        c_weight: event.target.elements.c_weight.value,
        fat: event.target.elements.fat.value,
        glychemicIndex: event.target.elements.glychemicIndex.value,
        allergens: event.target.elements.allergens.value,
        isRecommended: isRecommended
    }

    console.log(newIngredient);
    return;

    fetch("/ingredient/new", {
        method: "POST",

    })
}