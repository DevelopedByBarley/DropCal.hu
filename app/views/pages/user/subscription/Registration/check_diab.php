<div class="container">

    <div class="row  rounded mt-2  user-subscription-tab d-flex align-items-center justify-content-center">
        <div class="col">
            <form method="POST" action="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) + 1 ?>">
                <div class="user-subscription-content row d-flex align-items-center justify-content-center mb-5">
                    <div class="col-md-6 text-center d-flex align-items-center justify-content-center flex-column">
                        <h1 class="text-center mt-5 display-5 mb-4">Add meg hogy cukorbeteg vagy-e!</h1>
                        <p>
                            Felvesszük az adaid közé hogy cukorbeteg vagy-e, ennek figyelembe vételével
                            ajánlunk neked recepteket, hozzávalókat és figyelmeztetünk ha valamelyik számodra
                            nem optimális!
                        </p>
                        <p><b>Cukorbetegséged megjelöléséhez klikk az ikonra!</b></p>
                        <hr>
                        <div class="btn-group-inputs">
                            <div class="col-12 d-flex align-items-center justify-content-center">
                                <input type="checkbox" name="isHaveDiabetes" id="isHaveDiabetes" value="on">
                                <label for="isHaveDiabetes" class="mb-2">
                                    <img src="/public/assets/icons/candy.gif" style="height: 80px; width: 80px;" /> <span>Cukorbeteg vagyok!</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 user-navigation-buttons d-flex">
                        <a class="btn btn-outline-dark m-2 p-3" href="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) - 1 ?>">Vissza</a>
                        <button type="submit" class="btn btn-outline-dark m-2 p-3">Tovább</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>