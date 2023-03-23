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
    <div class="container">
        <div class="row">
            <div class="col" style="min-height: 79vh">
                <?= $params["content"] ?? "" ?>
            </div>
        </div>
    </div>
    <?php include 'includes/Footer.php' ?>
</body>

</html>