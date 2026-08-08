<?php

require "includes/session.php";
require "config/db.php";

// Rango de fechas (por defecto: mes actual)

$fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
$fecha_fin    = $_GET['fecha_fin'] ?? date('Y-m-d');

// Total de ventas en el rango

$query = "
    SELECT
        COUNT(*) AS cantidad_ventas,
        SUM(total) AS total_general
    FROM ventas
    WHERE DATE(fecha) BETWEEN ? AND ?
    AND estado = 'ACTIVA'
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
$stmt->execute();
$totales = $stmt->get_result()->fetch_assoc();

// Ventas por forma de pago

$query = "
    SELECT
        fp.forma_pago,
        COUNT(v.id_venta) AS cantidad,
        SUM(v.total) AS total
    FROM ventas v
    INNER JOIN formas_pago fp
        ON v.id_forma_pago = fp.id_forma_pago
    WHERE DATE(v.fecha) BETWEEN ? AND ?
    AND v.estado = 'ACTIVA'
    GROUP BY fp.forma_pago
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
$stmt->execute();
$formas_pago = $stmt->get_result();

// Ventas por día

$query = "
    SELECT
        DATE(fecha) AS dia,
        COUNT(*) AS cantidad,
        SUM(total) AS total
    FROM ventas
    WHERE DATE(fecha) BETWEEN ? AND ?
    AND estado = 'ACTIVA'
    GROUP BY DATE(fecha)
    ORDER BY dia DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
$stmt->execute();
$ventas_dia = $stmt->get_result();

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <h2 class="mb-4">
        <i class="fa-solid fa-chart-column"></i>
        Reportes de Ventas
    </h2>

    <div class="card mb-4">
        <div class="card-body">
            <form action="reportes.php" method="GET" class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control"
                        value="<?php echo htmlspecialchars($fecha_inicio); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" name="fecha_fin" class="form-control"
                        value="<?php echo htmlspecialchars($fecha_fin); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-body">
                    <h6>Total de Ventas</h6>
                    <h3>
                        <?php echo $totales['cantidad_ventas'] ?? 0; ?>
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-body">
                    <h6>Monto Total</h6>
                    <h3>
                        L. <?php echo number_format($totales['total_general'] ?? 0, 2); ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Ventas por método de pago</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Forma pago</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($forma = $formas_pago->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($forma['forma_pago']); ?></td>
                                    <td><?php echo $forma['cantidad']; ?></td>
                                    <td>L. <?php echo number_format($forma['total'], 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Ventas por día</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($dia = $ventas_dia->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $dia['dia']; ?></td>
                                    <td><?php echo $dia['cantidad']; ?></td>
                                    <td>L. <?php echo number_format($dia['total'], 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>