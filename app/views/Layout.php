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
    <?php include 'includes/Header.php' ?>
    <?php include 'components/Preloader.php' ?>
    <div class="container" style="margin-bottom: 100px;">
        <div class="row">
            <div class="col" style="min-height: 79vh">
                <?= $params["content"] ?? "" ?>
            </div>
        </div>
    </div>
    <div class="modal fade show" id="cookieModal" tabindex="-1" role="dialog" aria-labelledby="cookieModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cookieModalLabel">Cookie értesítés</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Bezárás">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Ez a weboldal sütiket használ a jobb felhasználói élmény érdekében.</p>
                    <p><a href="#">További információk</a></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
                    <button type="button" class="btn btn-primary" id="acceptCookie">Elfogadás</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'components/CookieModal.php' ?>
    <?php include 'includes/Footer.php' ?>
    <script src="public/js/fileReader.js"></script>
    <script src="public/js/preLoader.js"></script>
    <script src="public/js/cookieModalHandle.js"></script>
</body>

</html>