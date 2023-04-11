<div class="container-fluid welcome-container bg-light">
    <div class="row">
        <div class="col-12 welcome d-flex align-items-center justify-content-center">
            <h1 class="display-3">Üdvözöllek <?= $params["userName"] ?>!</h1>
        </div>
    </div>
</div>

<style>

    .welcome-container {
        min-height: 100vh;
        text-align: center;
        position: fixed;
        top: 0;
        left: 0;
    }

    .welcome {
        animation-name: fadeInWelcome;
        animation-duration: 6s;
        animation-fill-mode: forwards;
        height: 100vh;
    }

    @keyframes fadeInWelcome {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }

    }
</style>

<script>
    setTimeout(() => {
        window.location.href = "/"
    }, 5000)
</script>