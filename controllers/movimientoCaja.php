<?php
session_start();
require "../config/db.php";
require_once "../includes/validarController.php";
validarControlador($conn, "caja_movimientos");

// Verificamos que el usuario esté logueado

function redirigirConMensaje($mensaje, $tipo, $destino){
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipo'] = $tipo;
    header("Location: " . $destino);
    exit;
}
if (!isset($_SESSION['id_usuario'])) {
    redirigirConMensaje("Debe iniciar sesión para registrar movimientos de caja.", "danger", "../login.php");
}

$id_usuario = $_SESSION['id_usuario'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo        = $_POST['tipo'] ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');
    $monto       = floatval($_POST['monto'] ?? 0);

    // Validación básica
    if (empty($tipo) || empty($descripcion) || $monto <= 0) {
        redirigirConMensaje("Complete todos los datos del movimiento.", "danger", "../movimientosCaja.php");
    }

    // Buscamos la apertura de caja actual del usuario
    $query_apertura = "
        SELECT id_apertura
        FROM aperturas_caja
        WHERE id_usuario = ?
        AND estado = 'ABIERTA'
        LIMIT 1
    ";

    $stmt_apertura = $conn->prepare($query_apertura);
    $stmt_apertura->bind_param("i", $id_usuario);
    $stmt_apertura->execute();
    $resultado_apertura = $stmt_apertura->get_result();

    if ($resultado_apertura->num_rows === 0) {
        redirigirConMensaje("No tiene una caja abierta. Debe abrir una caja antes de registrar movimientos.", "warning", "../movimientosCaja.php");
    }

    $apertura = $resultado_apertura->fetch_assoc();
    $id_apertura = $apertura['id_apertura'];

    $stmt_apertura->close();


    $query_mov = "
        INSERT INTO movimientos_caja
        (
            id_apertura,
            id_usuario,
            tipo,
            descripcion,
            monto,
            fecha
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            NOW()
        )
    ";

    $stmt_mov = $conn->prepare($query_mov);
    $stmt_mov->bind_param(
        "iissd",
        $id_apertura,
        $id_usuario,
        $tipo,
        $descripcion,
        $monto
    );

    if ($stmt_mov->execute()) {
        redirigirConMensaje("Movimiento de caja registrado correctamente.", "success", "../movimientosCaja.php");
    } else {
        redirigirConMensaje("Error al registrar el movimiento de caja.", "danger", "../movimientosCaja.php");
    }

    $stmt_mov->close();
    $conn->close();

    header("Location: ../movimientosCaja.php");
    exit;

} else {
    header("Location: ../movimientosCaja.php");
    exit;
}
?>
