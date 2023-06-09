<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="/public/css/main.css?v=<?php echo time() ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <script src="/public/bootstrap/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>dropcal.hu</title>
</head>

<body>
    <div id="layout">
        <?php include 'includes/Header.php' ?>
        <?php include 'components/Preloader.php' ?>
        <div class="container-fluid" id="root" style="margin-bottom: 100px; margin-top: 3rem;">
            <div class="row">
                <div class="col" style="min-height: 79vh">
                    <?= $params["content"] ?? "" ?>
                </div>
            </div>
        </div>
        <div id="toast-container" style="position: fixed; bottom: 70px; right: 10px; z-index: 3"></div>
        <?php include 'includes/Footer.php' ?>
    </div>
    <?php include 'components/CookieModal.php' ?>
    <script src="public/js/registration/fileReader.js"></script>
    <script src="public/js/registration/emailVerification.js"></script>
    <script src="public/js/includes/preLoader.js"></script>
    <script src="public/js/includes/headerNavigation.js"></script>
    <script src="public/js/cookie/cookieModalHandle.js"></script>
    <script src="public/js/toast/Toast.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

</body>

</html>