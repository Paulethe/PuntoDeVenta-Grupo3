<?php

require "includes/session.php";
require "config/db.php";

if (!isset($_GET['id'])) {
    header("Location: roles.php");
    exit;
}

$id_rol = intval($_GET['id']);

// Obtener rol

$query = "SELECT * FROM roles WHERE id_rol = ?";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id_rol);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    header("Location: roles.php");
    exit;
}

$rol = $resultado->fetch_assoc();

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-user-shield"></i>
            Editar Rol
        </h2>
        <a href="roles.php" class="btn btn-secondary">
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
            <form action="controllers/editarRol.php" method="POST">

                <input type="hidden" name="id_rol" value="<?php echo $rol['id_rol']; ?>">

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" class="form-control" name="nombre" required
                        value="<?php echo htmlspecialchars($rol['nombre']); ?>">
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" class="form-control" name="descripcion" rows="3"><?php echo htmlspecialchars($rol['descripcion']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-control">
                        <option value="1" <?php if ($rol['estado'] == 1) echo "selected"; ?>>Activo</option>
                        <option value="0" <?php if ($rol['estado'] == 0) echo "selected"; ?>>Inactivo</option>
                    </select>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Actualizar Rol
                </button>
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>