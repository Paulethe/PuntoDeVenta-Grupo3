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
            <i class="fa-solid fa-truck"></i>
            Nuevo Proveedor
        </h2>
        <a href="proveedores.php" class="btn btn-secondary">
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

    <div class="card">
        <div class="card-body">
            <form action="controllers/guardarProveedor.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" class="form-control" name="nombre" placeholder="Ingrese el nombre del proveedor" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="empresa" class="form-label">Empresa</label>
                        <input type="text" id="empresa" class="form-control" name="empresa" placeholder="Ingrese el nombre de la empresa" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="identidad" class="form-label">Identidad / RTN</label>
                        <input type="text" id="identidad" class="form-control" name="identidad" placeholder="Ingrese identidad o RTN" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" class="form-control" name="telefono" placeholder="Ingrese el teléfono" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" id="correo" class="form-control" name="correo" placeholder="Ingrese el correo" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea id="direccion" class="form-control" name="direccion" rows="3" placeholder="Ingrese la dirección" required></textarea>
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
                    Guardar Proveedor
                </button>
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
