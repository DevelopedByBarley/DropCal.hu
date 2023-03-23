<div class="row user-registration-tab d-flex align-items-center justify-content-center bg-dark text-light">
    <div class="col">
        <form method="POST" action="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) + 1 ?>">
            <div class="user-registration-content row">
                <div class="col-md-6">
                    <h1 class="text-center mt-5 display-6">Köszöntelek a DropCalories regisztrációban!</h1>

                </div>
                <div class="col-md-5 d-flex align-items-center justify-content-center flex-column p-3">
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
            <div class="row">

                <div class="col-xs-12 user-navigation-buttons d-flex">
                    <button type="submit" class="btn btn-outline-light m-3 p-3">Vágjunk bele!</button>
                </div>
            </div>
        </form>
    </div>
</div>