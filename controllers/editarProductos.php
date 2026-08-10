<?php

session_start();
require "../config/db.php";
require_once "../includes/validarController.php";
validarControlador($conn, "productos_editar");

function redirigirConMensaje($mensaje, $tipo, $destino)
{
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipo'] = $tipo;
    header("Location: " . $destino);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    redirigirConMensaje("Solicitud no válida.", "warning", "../productos.php");
}

$id_producto = intval($_POST['id_producto']);
$codigo = trim($_POST['codigo']);
$codigo_barras = trim($_POST['codigo_barras']);
$nombre = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion']);
$id_categoria = intval($_POST['id_categoria']);
$precio_costo = floatval($_POST['precio_costo']);
$precio_venta = floatval($_POST['precio_venta']);
$stock = floatval($_POST['stock']);
$stock_minimo = floatval($_POST['stock_minimo']);
$stock_maximo = $_POST['stock_maximo'] !== '' ? floatval($_POST['stock_maximo']) : null;
$unidad_medida = trim($_POST['unidad_medida']);
$tipo = $_POST['tipo'];
$estado = intval($_POST['estado']);
$destino_error = "../editarProducto.php?id=" . $id_producto;

if (
    $id_producto <= 0 || empty($codigo) || empty($nombre) ||
    $id_categoria <= 0 || empty($unidad_medida) ||
    $_POST['precio_costo'] === '' || $_POST['precio_venta'] === '' ||
    $_POST['stock'] === '' || $_POST['stock_minimo'] === ''
) {
    redirigirConMensaje("Complete todos los campos obligatorios.", "danger", $destino_error);
}

if (
    $precio_costo < 0 || $precio_venta < 0 || $stock < 0 ||
    $stock_minimo < 0 || ($stock_maximo !== null && $stock_maximo < 0)
) {
    redirigirConMensaje("Los precios y cantidades no pueden ser negativos.", "danger", $destino_error);
}

if ($stock_maximo !== null && $stock_maximo < $stock_minimo) {
    redirigirConMensaje("El stock máximo no puede ser menor que el mínimo.", "danger", $destino_error);
}

if ($tipo != "Producto" && $tipo != "Servicio") {
    redirigirConMensaje("El tipo seleccionado no es válido.", "danger", $destino_error);
}

//

$consulta = "SELECT imagen FROM productos WHERE id_producto = ?";
$stmt = $conn->prepare($consulta);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    redirigirConMensaje("El producto no existe.", "warning", "../productos.php");
}

$producto = $resultado->fetch_assoc();
$imagen = $producto['imagen'];


$codigo_barras = $codigo_barras !== '' ? $codigo_barras : null;
$consulta = "SELECT id_producto FROM productos
             WHERE (codigo = ? OR codigo_barras = ?) AND id_producto != ?";
$stmt = $conn->prepare($consulta);
$stmt->bind_param("ssi", $codigo, $codigo_barras, $id_producto);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    redirigirConMensaje("El código o código de barras ya está registrado.", "warning", $destino_error);
}


if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
    $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extension, $permitidas)) {
        redirigirConMensaje("El formato de la imagen no es válido.", "danger", $destino_error);
    }

   $carpeta = "../assets/img/productos/";

    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0775, true);
    }

    $nombre_imagen = uniqid("producto_") . "." . $extension;

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta . $nombre_imagen)) {
        redirigirConMensaje("No se pudo guardar la imagen.", "danger", $destino_error);
    }

    $imagen = "assets/img/productos/" . $nombre_imagen;
}

// Actualizar producto

$query = "UPDATE productos SET
            codigo = ?, codigo_barras = ?, nombre = ?, descripcion = ?,
            id_categoria = ?, precio_costo = ?, precio_venta = ?, stock = ?,
            stock_minimo = ?, stock_maximo = ?, unidad_medida = ?, tipo = ?,
            imagen = ?, estado = ?
          WHERE id_producto = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param(
    "ssssidddddsssii",
    $codigo,
    $codigo_barras,
    $nombre,
    $descripcion,
    $id_categoria,
    $precio_costo,
    $precio_venta,
    $stock,
    $stock_minimo,
    $stock_maximo,
    $unidad_medida,
    $tipo,
    $imagen,
    $estado,
    $id_producto
);

if ($stmt->execute()) {
    redirigirConMensaje("Producto actualizado correctamente.", "success", "../productos.php");
} else {
    redirigirConMensaje("Error al actualizar el producto.", "danger", $destino_error);
}
