<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid text-center">
        <a class="navbar-brand border p-2" href="/">DropCal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (isset($params["userId"])) : ?>
                    <li class="nav-item dropdown profile-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Profil
                        </a>
                        <ul class="dropdown-menu text-center" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/user/profile">Profil</a></li>
                            <li><a class="dropdown-item" href="/diary/currentDiary?date=<?= date("Y-m-d") ?>">Napló</a></li>
                            <li><a class="dropdown-item" href="/ingredients">Ételeim</a></li>
                            <li><a class="dropdown-item" href="/user/recipes-dashboard">Receptjeim</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <?php if (!isset($params["userId"])) : ?>
                                <li class="text-center">
                                    <?php if (strpos($_SERVER["REQUEST_URI"], '/user/registration') === false) : ?>
                                        <a class="btn btn-outline-dark" href="/user/registration/<?= $params["currentStepId"] ?>">Regisztráció</a>
                                    <?php endif ?>
                                    <a class="btn btn-outline-dark" href="/user/login">Bejelentkezés</a>
                                </li>
                            <?php else : ?>
                                <li class="text-center">
                                    <a href="/user/logout" type="button" class="btn btn-outline-danger m-1">Kiejelentkezés</a>
                                    <a href="/user/change_profile" type="button" class="btn btn-outline-dark m-1">Felhasználó váltás</a>
                                </li>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endif ?>
                <?php if (isset($params["userId"])) : ?>
                   
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Kezdőlap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled bg-danger text-light" href="/recipes">Receptek</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled bg-danger text-light" href="#">Hírek</a>
                        </li>

                <?php endif ?>

            </ul>
            <?php if (!isset($params["userId"])) : ?>
                <div class="d-flex align-items-center justify-content-center">
                    <?php if (strpos($_SERVER["REQUEST_URI"], '/user/registration') === false) : ?>
                        <a class="btn btn-outline-dark" href="/user/registration/<?= $params["currentStepId"] ?>">Regisztráció</a>
                    <?php endif ?>
                    <a class="btn btn-outline-dark" href="/user/login">Bejelentkezés</a>
                </div>

            <?php else : ?>
                <div class="d-flex text-center">
                    <div class="profile-toggle-container">
                        <?php if (empty($params["profileImage"])) : ?>

                            <i class="bi bi-person-fill-gear profile-toggle" style="font-size: 2rem"></i>

                        <?php else : ?>
                            <img src="public/assets/profile_images/<?= $params["profileImage"] ?>" style="height: 50px; width: 50px; border-radius: 50%" class="profile-toggle" />
                        <?php endif ?>
                    </div>
                </div>
                <div id="profile-navigation" class="bg-light">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php if (empty($params["profileImage"])) : ?>

                                <i class="bi bi-person-fill-gear" style="font-size: 2rem"></i>

                            <?php else : ?>

                                <img src="public/assets/profile_images/<?= $params["profileImage"] ?>" style="height: 100px; width: 100px; border-radius: 50%" />
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-xs-12">
                            <ul class="list-group">
                                <a href="/user/profile" class="link no-underline">
                                    <li class="list-group-item profile-nav-listItem">Profil</li>
                                </a>
                                <a href="/diary/currentDiary?date=<?= date("Y-m-d") ?>" class="link no-underline">
                                    <li class="list-group-item profile-nav-listItem">Napló</li>
                                </a>
                                <a href="/ingredients" class="link no-underline">
                                    <li class="list-group-item profile-nav-listItem">Ételeim</li>
                                </a>
                                <a href="/user/recipes-dashboard" class="link no-underline">
                                    <li class="list-group-item profile-nav-listItem">Receptjeim</li>
                                </a>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            <a href="/user/logout" type="button" class="btn btn-outline-danger m-1">Kiejelentkezés</a>
                            <a href="/user/change_profile" type="button" class="btn btn-outline-dark m-1">Felhasználó váltás</a>
                        </div>
                    </div>
                    <div class="row mt-5 close-toggle">
                        <div class="col-xs-12 text-center">
                            <h1>
                                <i class="bi bi-x-circle" id="close-profile-nav"></i>
                            </h1>
                        </div>
                    </div>

                </div>
            <?php endif ?>
        </div>
    </div>
</nav>