<?php
// ============================================
// VISTA MOVIMIENTO DE CAJA
// Formulario + listado de movimientos
// ============================================

require "includes/session.php";   // Verifica sesión
require "config/db.php";          // Conexión

$id_usuario = $_SESSION['id_usuario'];

// Obtenemos la apertura de caja actual
$query_apertura = "
    SELECT id_apertura, fecha_apertura, monto_inicial
    FROM aperturas_caja
    WHERE id_usuario = ?
    AND estado = 'ABIERTA'
    LIMIT 1
";

$stmt_apertura = $conn->prepare($query_apertura);
$stmt_apertura->bind_param("i", $id_usuario);
$stmt_apertura->execute();
$result_apertura = $stmt_apertura->get_result();
$apertura = $result_apertura->fetch_assoc();
$stmt_apertura->close();

// Obtenemos movimientos de la caja actual (si existe)
$movimientos = [];
if ($apertura) {
    $id_apertura = $apertura['id_apertura'];

    $query_mov = "
        SELECT m.*, u.nombre AS nombre_usuario
        FROM movimientos_caja m
        INNER JOIN usuarios u
            ON m.id_usuario = u.id_usuario
        WHERE m.id_apertura = ?
        ORDER BY m.fecha DESC
    ";

    $stmt_mov = $conn->prepare($query_mov);
    $stmt_mov->bind_param("i", $id_apertura);
    $stmt_mov->execute();
    $result_mov = $stmt_mov->get_result();

    while ($fila = $result_mov->fetch_assoc()) {
        $movimientos[] = $fila;
    }

    $stmt_mov->close();
}

require "includes/header.php";
require "includes/navbar.php";
require "includes/sidebar.php";
?>

<div class="content">
    <div class="container-fluid mt-4">
        <h3>Movimientos de Caja</h3>
        <p class="text-muted">Registre ingresos y egresos de la caja actual.</p>

        <?php
        // Mensajes de sesión
        if (isset($_SESSION['mensaje'])) {
            $tipo = $_SESSION['tipo'] ?? 'info';
            ?>
            <div class="alert alert-<?php echo $tipo; ?> alert-dismissible fade show">
                <?php echo $_SESSION['mensaje']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['tipo']);
        }
        ?>

        <!-- Información de la caja actual -->
        <div class="card mb-3">
            <div class="card-body">
                <?php if ($apertura): ?>
                    <p><strong>Caja abierta:</strong> #<?php echo $apertura['id_apertura']; ?></p>
                    <p><strong>Fecha apertura:</strong> <?php echo $apertura['fecha_apertura']; ?></p>
                    <p><strong>Monto inicial:</strong> L. <?php echo number_format($apertura['monto_inicial'], 2); ?></p>
                <?php else: ?>
                    <p class="text-danger">
                        No tiene una caja abierta actualmente. Debe abrir una caja para registrar movimientos.
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulario para nuevo movimiento -->
        <div class="card mb-4">
            <div class="card-header">
                Registrar movimiento
            </div>
            <div class="card-body">
                <form action="controllers/movimientoCaja.php" method="POST">
                    <!-- Tipo de movimiento -->
                    <div class="mb-3">
                        <label class="form-label">Tipo de movimiento</label>
                        <select name="tipo" class="form-select" required <?php echo $apertura ? '' : 'disabled'; ?>>
                            <option value="">Seleccione...</option>
                            <option value="INGRESO">Ingreso</option>
                            <option value="EGRESO">Egreso</option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion"
                                  class="form-control"
                                  rows="2"
                                  required
                                  <?php echo $apertura ? '' : 'disabled'; ?>></textarea>
                    </div>

                    <!-- Monto -->
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="monto"
                               class="form-control"
                               required
                               <?php echo $apertura ? '' : 'disabled'; ?>>
                    </div>

                    <div class="d-grid">
                        <button type="submit"
                                class="btn btn-primary"
                                <?php echo $apertura ? '' : 'disabled'; ?>>
                            <i class="fa-solid fa-plus"></i> Registrar movimiento
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Listado de movimientos -->
        <div class="card">
            <div class="card-header">
                Movimientos de la caja actual
            </div>
            <div class="card-body">
                <?php if ($apertura && count($movimientos) > 0): ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Monto</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($movimientos as $mov): ?>
                                <tr>
                                    <td><?php echo $mov['fecha']; ?></td>
                                    <td><?php echo $mov['tipo']; ?></td>
                                    <td><?php echo htmlspecialchars($mov['descripcion']); ?></td>
                                    <td>L. <?php echo number_format($mov['monto'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($mov['nombre_usuario']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php elseif ($apertura): ?>
                    <p class="text-muted">No hay movimientos registrados para esta caja.</p>
                <?php else: ?>
                    <p class="text-muted">No hay caja abierta, por lo tanto no hay movimientos.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php
require "includes/footer.php";
$conn->close();
?>
