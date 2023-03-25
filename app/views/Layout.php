<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="/public/css/main.css?v=<?php echo time() ?>" />

    <script src="/public/bootstrap/js/bootstrap.js"></script>
    <title>dropcal.hu</title>
</head>

<body>
    <div id="layout" class="<?php echo isset($_COOKIE["cookie_accepted"]) ? '' : 'inactive' ?>">
        <?php include 'includes/Header.php' ?>
        <?php include 'components/Preloader.php' ?>
        <div class="container" id="root" style="margin-bottom: 100px;">
            <div class="row">
                <div class="col" style="min-height: 79vh">
                    <?= $params["content"] ?? "" ?>
                </div>
            </div>
        </div>

        <?php include 'includes/Footer.php' ?>
    </div>
        <?php include 'components/CookieModal.php' ?>
    <script src="public/js/fileReader.js"></script>
    <script src="public/js/preLoader.js"></script>
    <script src="public/js/cookieModalHandle.js"></script>
</body>

</html>