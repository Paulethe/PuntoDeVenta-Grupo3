<?php

require "includes/session.php";
require "config/db.php";

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-key"></i>
            Cambiar Contraseña
        </h2>
        <a href="perfil.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i>
            Regresar
        </a>
    </div>

    <?php if (isset($_SESSION['mensaje'])) { ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['tipo']); ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
    <?php
        unset($_SESSION['mensaje'], $_SESSION['tipo']);
    }
    ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <form action="controllers/actualizarPassword.php" method="POST" id="formPassword">

                        <div class="mb-3">
                            <label for="password_actual" class="form-label">Contraseña actual</label>
                            <input type="password" id="password_actual" class="form-control" name="password_actual" required autocomplete="current-password">
                        </div>

                        <div class="mb-3">
                            <label for="password_nueva" class="form-label">Nueva contraseña</label>
                            <input type="password" id="password_nueva" class="form-control" name="password_nueva" required minlength="8" autocomplete="new-password">
                            <div class="form-text">Mínimo 8 caracteres.</div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmar" class="form-label">Confirmar nueva contraseña</label>
                            <input type="password" id="password_confirmar" class="form-control" name="password_confirmar" required minlength="8" autocomplete="new-password">
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Actualizar contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('formPassword').addEventListener('submit', function (e) {
        const nueva = document.getElementById('password_nueva').value;
        const confirmar = document.getElementById('password_confirmar').value;

        if (nueva !== confirmar) {
            e.preventDefault();
            alert('La nueva contraseña y su confirmación no coinciden.');
        }
    });
</script>

<?php include "includes/footer.php"; ?>
