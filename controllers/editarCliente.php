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

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    redirigirConMensaje("Solicitud no válida.", "warning", "../clientes.php");
}

$id_cliente = intval($_POST['id_cliente']);
$nombre = trim($_POST['nombre']);
$identidad = trim($_POST['identidad']);
$telefono = trim($_POST['telefono']);
$correo = trim($_POST['correo']);
$direccion = trim($_POST['direccion']);
$limite_credito = floatval($_POST['limite_credito']);
$saldo_credito = floatval($_POST['saldo_credito']);
$estado = intval($_POST['estado']);
$destino_error = "../editarCliente.php?id=" . $id_cliente;

// Validar campos obligatorios

if ($id_cliente <= 0 || empty($nombre) || $_POST['limite_credito'] === '' || $_POST['saldo_credito'] === '')
    {
    redirigirConMensaje(
        "Complete todos los campos obligatorios.",
        "danger",
        $destino_error
    );
}

if ($limite_credito < 0 || $saldo_credito < 0) {
    redirigirConMensaje( "El límite y el saldo de crédito no pueden ser negativos.", "danger", $destino_error);
}

if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    redirigirConMensaje("El correo no es válido.", "danger", $destino_error);
}

if (!empty($identidad) && (strlen($identidad) !== 13 || !ctype_digit($identidad))) {
    redirigirConMensaje("La identidad debe tener exactamente 13 dígitos y contener solo números.", "danger", $destino_error);
}

// Verificar que el cliente este en la base de datos
$consulta = "SELECT id_cliente FROM clientes WHERE id_cliente = ?";
$stmt = $conn->prepare($consulta);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    redirigirConMensaje("El cliente no existe.", "warning", "../clientes.php");
}

// Verificar identidad duplicada
$identidad = $identidad !== '' ? $identidad : null;
if ($identidad !== null) {
    $consulta = "SELECT id_cliente FROM clientes WHERE identidad = ? AND id_cliente != ?";
    $stmt = $conn->prepare($consulta);
    $stmt->bind_param("si", $identidad, $id_cliente);
    $stmt->execute();

    if ($stmt->get_result()->num_rows > 0) {
        redirigirConMensaje("Ya existe otro cliente con esa identidad.", "warning", $destino_error);
    }
}

// Actualizar cliente

$query = "UPDATE clientes SET
            nombre = ?,
            identidad = ?,
            telefono = ?,
            correo = ?,
            direccion = ?,
            limite_credito = ?,
            saldo_credito = ?,
            estado = ?
          WHERE id_cliente = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param(
    "sssssddii",
    $nombre,
    $identidad,
    $telefono,
    $correo,
    $direccion,
    $limite_credito,
    $saldo_credito,
    $estado,
    $id_cliente
);

if ($stmt->execute()) {
    redirigirConMensaje("Cliente actualizado correctamente.", "success", "../clientes.php");
} else {
    redirigirConMensaje("Error al actualizar el cliente.", "danger", $destino_error);
}
