<?php

require "includes/session.php";
require "config/db.php";

$query = "
    SELECT
        id_categoria,
        categoria,
        descripcion,
        estado,
        fecha_creacion,
        fecha_actualizacion
    FROM categorias
    ORDER BY id_categoria DESC
";

$resultado = $conn->query($query);

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-tags"></i>
            Categorías
        </h2>

        <a class="btn btn-primary" href="nuevaCategoria.php">
            <i class="fa-solid fa-plus"></i>
            Nueva Categoría
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha creación</th>
                            <th>Fecha actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($categoria = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $categoria['id_categoria']; ?></td>
                                <td><?php echo htmlspecialchars($categoria['categoria']); ?></td>
                                <td><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
                                <td>
                                    <?php if ($categoria['estado'] == 1) { ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $categoria['fecha_creacion']; ?></td>
                                <td><?php echo $categoria['fecha_actualizacion'] ?? ''; ?></td>

                                <td class="text-nowrap">
                                    <a href="editarCategoria.php?id=<?php echo $categoria['id_categoria']; ?>"
                                        class="btn btn-primary btn-sm" title="Editar categoría">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <?php if ($categoria['estado'] == 1) { ?>
                                        <a href="controllers/eliminarCategoria.php?id=<?php echo $categoria['id_categoria']; ?>"
                                            class="btn btn-danger btn-sm" title="Eliminar categoría"
                                            onclick="return confirm('¿Desea desactivar esta categoría?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
