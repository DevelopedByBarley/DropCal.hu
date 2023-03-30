<div class="row" style="min-height: 75vh">
    <form action="/user/login/<?= $params["userId"] ?>" method="POST" class="d-flex align-items-center justify-content-center flex-column">
        <h1 class="display-6 mb-5">Az email címedre hitelesítőkódot küldtünk</h1>
        <div class="col-xs-12 mt-5">
            <label class="form-label" for="verification">Hitelesítőkód beírása</label>
            <input type="number" name="code" id="verification" class="form-control" />
        </div>
        <button type="submit" class="btn btn-outline-dark mt-3">Elküld</button>
    </form>
</div>