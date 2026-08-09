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

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirigirConMensaje("Solicitud no válida.", "warning", "../productos.php");
    }
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

    if (
        empty($codigo) ||
        empty($nombre) ||
        $id_categoria <= 0 ||
        empty($unidad_medida) ||
        $_POST['precio_costo'] === '' ||
        $_POST['precio_venta'] === '' ||
        $_POST['stock'] === '' ||
        $_POST['stock_minimo'] === ''
    ) {
        redirigirConMensaje("Complete correctamente todos los campos obligatorios.", "danger", "../nuevoProducto.php");
    }



    if (
        $precio_costo < 0 ||
        $precio_venta < 0 ||
        $stock < 0 ||
        $stock_minimo < 0 ||
        ($stock_maximo !== null && $stock_maximo < 0)
    ) {
        redirigirConMensaje("Los precios y las cantidades de stock no deben ser negativos.", "danger", "../nuevoProducto.php");
    }

    if (!in_array($tipo, ['Producto', 'Servicio'], true)) {
        redirigirConMensaje("El tipo seleccionado no es válido.", "danger", "../nuevoProducto.php");
    }

//
    $codigo_barras = $codigo_barras !== '' ? $codigo_barras : null;

    $stmt = $conn->prepare(
        "SELECT id_categoria
        FROM categorias
        WHERE id_categoria = ? AND estado = 1"
    );
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();

    if ($stmt->get_result()->num_rows === 0) {
        $stmt->close();
        redirigirConMensaje("La categoría seleccionada no existe", "warning", "../nuevoProducto.php");
    }

$stmt->close();
$stmt = $conn->prepare(
    "SELECT codigo, codigo_barras
     FROM productos
     WHERE codigo = ? OR codigo_barras = ?
     LIMIT 1"
);
$stmt->bind_param("ss", $codigo, $codigo_barras);
$stmt->execute();
$producto_existente = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($producto_existente) {
    $campo_duplicado = $producto_existente['codigo'] === $codigo ? 'código' : 'código de barras';

    redirigirConMensaje("Ya existe un producto con ese " . $campo_duplicado . ".", "warning", "../nuevoProducto.php");
}

$ruta_imagen = null;
$ruta_imagen_absoluta = null;
$archivo_imagen = $_FILES['imagen'] ?? null;

if ($archivo_imagen && $archivo_imagen['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($archivo_imagen['error'] !== UPLOAD_ERR_OK) {
        redirigirConMensaje("No se pudo cargar la imagen seleccionada.", "danger", "../nuevoProducto.php");
    }

    /*$tamano_maximo = 10 * 1024 * 1024;

    if ($archivo_imagen['size'] > $tamano_maximo) {
        redirigirConMensaje("La imagen no puede superar los 10 MB.", "danger", "../nuevoProducto.php");
    }*/

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($archivo_imagen['tmp_name']);
    $extensiones_permitidas = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];

    if (!isset($extensiones_permitidas[$mime])) {
        redirigirConMensaje("La imagen debe tener formato JPG, PNG, GIF o WEBP.", "danger", "../nuevoProducto.php");
    }

    $directorio_imagenes = dirname(__DIR__) . DIRECTORY_SEPARATOR
        . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'productos';

    if (!is_dir($directorio_imagenes) && !mkdir($directorio_imagenes, 0775, true)) {
        redirigirConMensaje("No se pudo preparar el directorio para guardar la imagen.","danger", "../nuevoProducto.php");
    }

    $nombre_imagen = bin2hex(random_bytes(16)) . '.' . $extensiones_permitidas[$mime];
    $ruta_imagen_absoluta = $directorio_imagenes . DIRECTORY_SEPARATOR . $nombre_imagen;
    $ruta_imagen = 'assets/img/productos/' . $nombre_imagen;

    if (!move_uploaded_file($archivo_imagen['tmp_name'], $ruta_imagen_absoluta)) {
        redirigirConMensaje("No se pudo guardar la imagen del producto.","danger",
            "../nuevoProducto.php"
        );
    }

}


$query = "INSERT INTO productos (
                  codigo,
                  codigo_barras,
                  nombre,
                  descripcion,
                  id_categoria,
                  precio_costo,
                  precio_venta,
                  stock,
                  stock_minimo,
                  stock_maximo,
                  unidad_medida,
                  tipo,
                  imagen,
                  estado
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "ssssidddddsssi",
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
        $ruta_imagen,
        $estado
    );
    if ($stmt->execute()) {
        redirigirConMensaje("Producto registrado correctamente.", "success", "../productos.php");
    } else {
        redirigirConMensaje("Error al guardar el producto.", "danger", "../nuevoProducto.php");
    }
