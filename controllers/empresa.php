<?php

session_start();

require "../config/db.php";
require_once "../includes/validarController.php";
validarControlador($conn, "empresa_editar");

function redirigirConMensaje($mensaje, $tipo, $destino)
{
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipo'] = $tipo;
    header("Location: " . $destino);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    redirigirConMensaje("Solicitud no válida.", "warning", "../empresa.php");
}

    $nombre = trim($_POST['nombre']);
    $razon_social = trim($_POST['razon_social']);
    $nombre_comercial = trim($_POST['nombre_comercial']);
    $rtn = trim($_POST['rtn']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $departamento = trim($_POST['departamento']);
    $municipio = trim($_POST['municipio']);
    $direccion = trim($_POST['direccion']);
    $logo = trim($_POST['logo_actual']);
    $estado = $_POST['estado'] ?? 'A';


if (empty($nombre)) {
    redirigirConMensaje(
        "El nombre de la empresa es obligatorio.",
        "danger",
        "../empresa.php"
    );
}

if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    redirigirConMensaje("El correo no es válido.", "danger", "../empresa.php");
}

// Guardar un logo nuevo si fue seleccionado
if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
    $extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $permitidas)) {
        redirigirConMensaje(
            "El logo debe tener formato JPG, PNG o WEBP.",
            "danger",
            "../empresa.php"
        );
    }

    $carpeta = "../assets/img/empresa/";

    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0775, true);
    }

    $nombre_logo = uniqid("logo_") . "." . $extension;
    if (!move_uploaded_file($_FILES['logo']['tmp_name'], $carpeta . $nombre_logo)) {
        redirigirConMensaje("No se pudo guardar el logo.", "danger", "../empresa.php");
    }

    $logo = "assets/img/empresa/" . $nombre_logo;
}

$consulta = "SELECT id_empresa FROM empresa WHERE id_empresa = 1";
$resultado = $conn->query($consulta);

if ($resultado->num_rows > 0) {
    $query = "UPDATE empresa SET
                nombre = ?, razon_social = ?, nombre_comercial = ?, rtn = ?,
                telefono = ?, correo = ?, departamento = ?, municipio = ?,
                direccion = ?, logo = ?, estado = ?
              WHERE id_empresa = 1";
} else {
    $query = "INSERT INTO empresa
              (id_empresa, nombre, razon_social, nombre_comercial, rtn,
               telefono, correo, departamento, municipio, direccion, logo, estado)
              VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
}

$stmt = $conn->prepare($query);
$stmt->bind_param(
    "sssssssssss",
    $nombre,
    $razon_social,
    $nombre_comercial,
    $rtn,
    $telefono,
    $correo,
    $departamento,
    $municipio,
    $direccion,
    $logo,
    $estado
);

if ($stmt->execute()) {
    redirigirConMensaje(
        "Datos de la empresa guardados correctamente.",
        "success",
        "../empresa.php"
    );
} else {
    redirigirConMensaje(
        "Error al guardar los datos de la empresa.",
        "danger",
        "../empresa.php"
    );
}
