<?php

require "includes/session.php";
require "config/db.php";

$query = "
    SELECT
        id_rol,
        nombre,
        descripcion,
        estado,
        fecha_creacion
    FROM roles
    ORDER BY id_rol DESC
";

$resultado = $conn->query($query);

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-user-shield"></i>
            Roles
        </h2>

        <a class="btn btn-primary" href="nuevoRol.php">
            <i class="fa-solid fa-plus"></i>
            Nuevo Rol
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
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($rol = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $rol['id_rol']; ?></td>
                                <td><?php echo htmlspecialchars($rol['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($rol['descripcion']); ?></td>
                                <td>
                                    <?php if ($rol['estado'] == 1) { ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $rol['fecha_creacion']; ?></td>

                                <td class="text-nowrap">
                                    <a href="editarRol.php?id=<?php echo $rol['id_rol']; ?>"
                                        class="btn btn-primary btn-sm" title="Editar rol">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <?php if ($rol['estado'] == 1) { ?>
                                        <a href="controllers/eliminarRol.php?id=<?php echo $rol['id_rol']; ?>"
                                            class="btn btn-danger btn-sm" title="Eliminar rol"
                                            onclick="return confirm('¿Desea desactivar este rol?')">
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