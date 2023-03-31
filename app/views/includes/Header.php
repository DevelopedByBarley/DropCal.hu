<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand border p-2" href="/">DropCal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Kezdőlap</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li>
            </ul>
            <?php if (!isset($params["userId"])) : ?>
                <div class="d-flex">
                    <a class="btn btn-outline-dark" href="/user/registration/<?= $params["currentStepId"] ?>">Regisztráció</a>
                    <a class="btn btn-outline-dark" href="/user/login">Bejelentkezés</a>
                </div>

            <?php else : ?>
                <div class="d-flex">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="profile-icon-dropdown">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if (empty($params["profileImage"])) : ?>

                                    <i class="bi bi-person-fill-gear" style="font-size: 2rem"></i>

                                <?php else : ?>
                                    <img src="public/assets/profile_images/<?= $params["profileImage"] ?>" style="height: 50px; width: 50px; border-radius: 50%" />
                                <?php endif ?>
                            </a>
                            <ul class="dropdown-menu" style="width: 200px;" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="text-center"><a class="btn btn-outline-danger" href="/user/logout">Kijelentkezés</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="text-center"><a class="btn btn-info text-light" href="/user/change_profile">Felhasználó váltás</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            <?php endif ?>
        </div>
    </div>
</nav>

<style>
    @media(min-width: 768px) {
        #profile-icon-dropdown {
            position: relative;
            right: 100px;
        }
    }
</style>