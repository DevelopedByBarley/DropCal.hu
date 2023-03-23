<div class="row user-registration-tab">
    <div class="col">
        <form method="POST" action="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) + 1 ?>">
            <div class="user-registration-content">
                <h1>Allergens</h1>
            </div>
            <div class="btn-group user-navigation-buttons">
                <button type="submit" class="btn btn-outline-dark m-3">Kövi</button>
                <a class="btn btn-outline-dark m-3 " href="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) - 1 ?>">Vissza</a>
            </div>
        </form>
    </div>
</div>