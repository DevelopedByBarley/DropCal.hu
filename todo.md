2. Email cím validálása mielőtt elküldjük a regisztrációt.

Gomb  amire ha rákattintunk elküld a php-nak egy kérést, hogy küldjön az emailjére egy kódot ez a kód a sessionbe mentődik
ezután megjelenik egy input field amibe bele lehet rakni a kódot , ha a sessionből kiolvasott kód megegyezik a beírt kóddal akkor 
az email hitelesített, más esetben nem hitelesített.
Ha hitelesített a backend küldje vissza azt a szöveget hogy hitelesített.
Más esetben azt hogy a hitelesítés nem sikerült
Ha nem hitelesített ne engedje tovább a felhasználót a form-ról ha hitelesített akkor mehet.

Gomb megnyomjuk => kód kenerálása => sessionbe mentődik a kód => email kimegy        => |
                |                                                                       | =>  
                => input field megjeleni => beírjuk a kódot amit az emailből kapunk  => |