<?php
$user = $params["user"];
var_dump($user);
?>


<!-- Jumbotron -->
<div class="p-4 shadow-4 rounded-3" style="background-color: hsl(0, 0%, 94%);">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="text-center display-6">Üdvözöllek <?= $user["userName"] ?></h1>
        </div>
    </div>
</div>
<!-- Jumbotron -->