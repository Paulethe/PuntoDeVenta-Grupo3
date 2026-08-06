<?php

require "includes/session.php";
require "config/db.php";

$query = "
    SELECT
        id_cliente,
        nombre,
        identidad,
        telefono,
        correo,
        direccion,
        limite_credito,
        saldo_credito,
        estado,
        fecha_creacion,
        fecha_actualizacion
    FROM clientes
    ORDER BY id_cliente DESC
";

$resultado = $conn->query($query);

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-users"></i>
            Clientes
        </h2>

        <a class="btn btn-primary" href="nuevoCliente.php">
            <i class="fa-solid fa-user-plus"></i>
            Nuevo Cliente
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Identidad</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Dirección</th>
                            <th>Límite crédito</th>
                            <th>Saldo crédito</th>
                            <th>Estado</th>
                            <th>Fecha creación</th>
                            <th>Fecha actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cliente = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $cliente['id_cliente']; ?></td>
                                <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['identidad']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['correo']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['direccion']); ?></td>
                                <td><?php echo number_format($cliente['limite_credito'], 2); ?></td>
                                <td><?php echo number_format($cliente['saldo_credito'], 2); ?></td>
                                <td>
                                    <?php if ($cliente['estado'] == 1) { ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $cliente['fecha_creacion']; ?></td>
                                <td><?php echo $cliente['fecha_actualizacion'] ?? ''; ?></td>
                                
                                <td class="text-nowrap">
                                    <a href="editarCliente.php?id=<?php echo $cliente['id_cliente']; ?>"
                                        class="btn btn-primary btn-sm" title="Editar cliente">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <?php if ($cliente['estado'] == 1) { ?>
                                        <a href="controllers/eliminarCliente.php?id=<?php echo $cliente['id_cliente']; ?>"
                                            class="btn btn-danger btn-sm" title="Eliminar cliente"
                                            onclick="return confirm('¿Desea desactivar este cliente?')">
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
