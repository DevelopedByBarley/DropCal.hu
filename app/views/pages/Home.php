<?php
$user = $params["user"] ?? '';
?>


<!-- Jumbotron -->
<div class="p-4 shadow-4 rounded-3" style="background-color: hsl(0, 0%, 94%);">
    <div class="row">
        <div class="col-xs-12"></div>
        <h1 class="text-center display-6">Üdvözöllek <?= $user["userName"] ?? "a DropCalories.hu weboldalán!" ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php if (isset($_SESSION["userId"])) : ?>
            <form method="POST" action="/diary/change_date">
                <label for="datum">Dátum:</label>
                <input type="date" id="datum" name="date" onchange="this.form.submit()" value="<?php echo isset($_POST['date']) ? $_POST['date'] : date('Y-m-d'); ?>">
            </form>
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <h1>Ma ennyi kalóriát fogyasztottál!: <?= $params["userDiary"]["sumOfCalories"] ?></h1>
    </div>
</div>



<!-- Jumbotron -->