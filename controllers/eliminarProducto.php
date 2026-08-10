<?php

session_start();

require "../config/db.php";
require_once "../includes/validarController.php";
validarControlador($conn, "productos_eliminar");

function redirigirConMensaje($mensaje, $tipo, $destino)
{
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipo'] = $tipo;
    header("Location: " . $destino);
    exit;
}

if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
    redirigirConMensaje("Producto no válido.", "warning", "../productos.php");
}

$id_producto = intval($_GET['id']);

// Verificar que el producto exista

$consulta = "SELECT id_producto FROM productos WHERE id_producto = ?";
$stmt = $conn->prepare($consulta);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    redirigirConMensaje("El producto no existe.", "warning", "../productos.php");
}

// Desactivar producto
$query = "UPDATE productos SET estado = 0 WHERE id_producto = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_producto);

if ($stmt->execute()) {
    redirigirConMensaje( "Producto desactivado correctamente.", "success", "../productos.php");
} else {
    redirigirConMensaje("Error al desactivar el producto.", "danger", "../productos.php");
}
