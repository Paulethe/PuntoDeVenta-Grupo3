<?php

require "includes/session.php";
require "config/db.php";

if (!isset($_GET['id'])) {
    header("Location: proveedores.php");
    exit;
}

$id_proveedor = intval($_GET['id']);

// Obtener proveedor

$query = "SELECT * FROM proveedores WHERE id_proveedor = ?";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id_proveedor);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    header("Location: proveedores.php");
    exit;
}

$proveedor = $resultado->fetch_assoc();

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-truck"></i>
            Editar Proveedor
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
            <form action="controllers/editarProveedor.php" method="POST">

                <input type="hidden" name="id_proveedor" value="<?php echo $proveedor['id_proveedor']; ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" class="form-control" name="nombre" required
                            value="<?php echo htmlspecialchars($proveedor['nombre']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="empresa" class="form-label">Empresa</label>
                        <input type="text" id="empresa" class="form-control" name="empresa" required
                            value="<?php echo htmlspecialchars($proveedor['empresa']); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="identidad" class="form-label">Identidad / RTN</label>
                        <input type="text" id="identidad" class="form-control" name="identidad" required
                            value="<?php echo htmlspecialchars($proveedor['identidad']); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" class="form-control" name="telefono" required
                            value="<?php echo htmlspecialchars($proveedor['telefono']); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" id="correo" class="form-control" name="correo" required
                            value="<?php echo htmlspecialchars($proveedor['correo']); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea id="direccion" class="form-control" name="direccion" required rows="3"><?php echo htmlspecialchars($proveedor['direccion']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-control">
                        <option value="1" <?php if ($proveedor['estado'] == 1) echo "selected"; ?>>Activo</option>
                        <option value="0" <?php if ($proveedor['estado'] == 0) echo "selected"; ?>>Inactivo</option>
                    </select>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Actualizar Proveedor
                </button>
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
