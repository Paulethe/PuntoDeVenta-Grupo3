<?php

require "includes/session.php";
require "config/db.php";

$query = "
    SELECT
        id_proveedor,
        nombre,
        empresa,
        identidad,
        telefono,
        correo,
        direccion,
        estado,
        fecha_creacion,
        fecha_actualizacion
    FROM proveedores
    ORDER BY id_proveedor DESC
";

$resultado = $conn->query($query);

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-truck"></i>
            Proveedores
        </h2>

        <a class="btn btn-primary" href="nuevoProveedor.php">
            <i class="fa-solid fa-plus"></i>
            Nuevo Proveedor
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
                            <th>Empresa</th>
                            <th>Identidad</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Dirección</th>
                            <th>Estado</th>
                            <th>Fecha creación</th>
                            <th>Fecha actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($proveedor = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $proveedor['id_proveedor']; ?></td>
                                <td><?php echo htmlspecialchars($proveedor['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['empresa']); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['identidad']); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['correo']); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['direccion']); ?></td>
                                <td>
                                    <?php if ($proveedor['estado'] == 1) { ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $proveedor['fecha_creacion']; ?></td>
                                <td><?php echo $proveedor['fecha_actualizacion'] ?? ''; ?></td>

                                <td class="text-nowrap">
                                    <a href="editarProveedor.php?id=<?php echo $proveedor['id_proveedor']; ?>"
                                        class="btn btn-primary btn-sm" title="Editar proveedor">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <?php if ($proveedor['estado'] == 1) { ?>
                                        <a href="controllers/eliminarProveedor.php?id=<?php echo $proveedor['id_proveedor']; ?>"
                                            class="btn btn-danger btn-sm" title="Eliminar proveedor"
                                            onclick="return confirm('¿Desea desactivar este proveedor?')">
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
