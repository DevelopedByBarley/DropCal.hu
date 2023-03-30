<div class="row  rounded mt-2 user-subscription-tab d-flex align-items-center justify-content-center">
    <div class="col">
        <form method="POST" action="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) + 1 ?>">
            <div class="user-subscription-content row ">
                <div class="col-12 d-flex align-items-center justify-content-center flex-column">
                    <img src="/public/assets/icons/vegan.gif" style="height: 150px; width: 150px;" />
                </div>
                <div class="col-md-6 text-center">
                    <h1 class="text-center mt-5 display-5 mb-4">Köszöntelek a DropCalories regisztrációban!</h1>
                </div>
                <div class="col-md-5 d-flex align-items-center justify-content-center flex-column p-3 mb-5">
                    <p>
                        Ez a weboldal segítséget nyújt arra, hogy nyomon kövesd az étkezésed és a kalóriabeviteled.
                        Legyél te akár fogyni vágyó, cukorbeteg vagy allergénekkel rendelkező, itt megtalálod a számításaidat!
                    </p>
                    <p>
                        A regisztráció során feljegyezzük adataidat amelyek segítségével egyénileg testre szabhatjuk
                        a céljaidat mindegy hogy testsúly csökkentéséről, emeléséről vagy csak szimplán az egészséges
                        életmód követéséről legyen szó.
                    </p>
                </div>
            </div>
            <div class="row mt-5">

                <div class="col-xs-12 user-navigation-buttons d-flex">
                    <button type="submit" class="btn btn-outline-dark m-3 p-3">Vágjunk bele!</button>
                </div>
            </div>
        </form>
    </div>
</div>