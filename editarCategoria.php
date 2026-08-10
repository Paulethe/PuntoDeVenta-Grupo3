<?php

require "includes/session.php";
require "config/db.php";

if (!isset($_GET['id'])) {
    header("Location: categorias.php");
    exit;
}

$id_categoria = intval($_GET['id']);

// Obtener categoría

$query = "SELECT * FROM categorias WHERE id_categoria = ?";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id_categoria);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    header("Location: categorias.php");
    exit;
}

$categoria = $resultado->fetch_assoc();

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-tags"></i>
            Editar Categoría
        </h2>
        <a href="categorias.php" class="btn btn-secondary">
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
            <form action="controllers/editarCategoria.php" method="POST">

                <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">

                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <input type="text" id="categoria" class="form-control" name="categoria" required
                        value="<?php echo htmlspecialchars($categoria['categoria']); ?>">
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" class="form-control" name="descripcion" required rows="3"><?php echo htmlspecialchars($categoria['descripcion']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-control">
                        <option value="1" <?php if ($categoria['estado'] == 1) echo "selected"; ?>>Activo</option>
                        <option value="0" <?php if ($categoria['estado'] == 0) echo "selected"; ?>>Inactivo</option>
                    </select>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Actualizar Categoría
                </button>
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
