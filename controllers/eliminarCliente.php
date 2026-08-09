<?php

session_start();

require "../config/db.php";
function redirigirConMensaje($mensaje, $tipo, $destino)
{
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipo'] = $tipo;
    header("Location: " . $destino);
    exit;
}

if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
    redirigirConMensaje("Cliente no válido.", "warning", "../clientes.php");
}

$id_cliente = intval($_GET['id']);

// Verificar que el cliente exista

$consulta = "SELECT id_cliente FROM clientes WHERE id_cliente = ?";
$stmt = $conn->prepare($consulta);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    redirigirConMensaje("El cliente no existe.", "warning", "../clientes.php");
}

// Desactivar cliente
$query = "UPDATE clientes SET estado = 0 WHERE id_cliente = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_cliente);

if ($stmt->execute()) {
    redirigirConMensaje( "Cliente desactivado correctamente.", "success", "../clientes.php");
} else {
    redirigirConMensaje("Error al desactivar el cliente.", "danger", "../clientes.php");
}
