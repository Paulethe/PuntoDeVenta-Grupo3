<?php

require "includes/session.php";
require "config/db.php";

$query = "
    SELECT
        a.id_apertura,
        c.nombre AS caja,
        u.nombre AS usuario,
        a.fecha_apertura,
        a.fecha_cierre,
        a.monto_inicial,
        a.monto_final,
        a.diferencia,
        a.estado
    FROM aperturas_caja a
    INNER JOIN cajas c
        ON a.id_caja = c.id_caja
    INNER JOIN usuarios u
        ON a.id_usuario = u.id_usuario
    ORDER BY a.id_apertura DESC
";

$resultado = $conn->query($query);

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-lock"></i>
            Cierres de Caja
        </h2>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Caja</th>
                            <th>Usuario</th>
                            <th>Fecha apertura</th>
                            <th>Fecha cierre</th>
                            <th>Monto inicial</th>
                            <th>Monto final</th>
                            <th>Diferencia</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($caja = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $caja['id_apertura']; ?></td>
                                <td><?php echo htmlspecialchars($caja['caja']); ?></td>
                                <td><?php echo htmlspecialchars($caja['usuario']); ?></td>
                                <td><?php echo $caja['fecha_apertura']; ?></td>
                                <td><?php echo $caja['fecha_cierre'] ?? '-'; ?></td>
                                <td>L. <?php echo number_format($caja['monto_inicial'], 2); ?></td>
                                <td>
                                    <?php if ($caja['monto_final'] !== null) { ?>
                                        L. <?php echo number_format($caja['monto_final'], 2); ?>
                                    <?php } else { ?>
                                        -
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($caja['diferencia'] !== null) { ?>
                                        L. <?php echo number_format($caja['diferencia'], 2); ?>
                                    <?php } else { ?>
                                        -
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($caja['estado'] == 'ABIERTA') { ?>
                                        <span class="badge bg-success">Abierta</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">Cerrada</span>
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