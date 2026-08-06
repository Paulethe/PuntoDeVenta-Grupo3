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
            <i class="fa-solid fa-user-plus"></i>
            Nuevo Cliente
        </h2>
        <a href="clientes.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i>
            Regresar
        </a>
    </div>

    <?php if (isset($_SESSION['mensaje'])) { ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['tipo'], ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php
        unset($_SESSION['mensaje'], $_SESSION['tipo']);
    }
    ?>

    <div class="card">
        <div class="card-body">
            <form action="controllers/guardarCliente.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" id="nombre" class="form-control" name="nombre" placeholder="Ingrese el nombre completo" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="identidad" class="form-label">Identidad</label>
                        <input type="text" id="identidad" class="form-control" name="identidad" placeholder="Ingrese el número de identidad">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" class="form-control" name="telefono" placeholder="Ingrese el teléfono">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" id="correo" class="form-control" name="correo" placeholder="correo@ejemplo.com">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea id="direccion" class="form-control" name="direccion" rows="3" placeholder="Ingrese la dirección"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="limite_credito" class="form-label">Límite de crédito</label>
                        <input type="number" id="limite_credito" class="form-control" name="limite_credito" min="0" step="0.01" value="0.00" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="saldo_credito" class="form-label">Saldo de crédito</label>
                        <input type="number" id="saldo_credito" class="form-control" name="saldo_credito" min="0" step="0.01" value="0.00" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-control">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Guardar Cliente
                </button>
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
