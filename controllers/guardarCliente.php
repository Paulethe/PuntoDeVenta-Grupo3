<?php
session_start();
require "../config/db.php";
require_once "../includes/validarController.php";
validarControlador($conn, "clientes_crear");


function redirigirConMensaje($mensaje, $tipo, $destino){
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipo'] = $tipo;
    header("Location: " . $destino);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigirConMensaje("Solicitud no válida.", "warning", "../clientes.php");
}

    $nombre = trim($_POST['nombre']);
    $identidad = trim($_POST['identidad']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);
    $limite_credito = floatval($_POST['limite_credito']);
    $saldo_credito = floatval($_POST['saldo_credito']);
    $estado = intval($_POST['estado']);



    if (
        empty($nombre) ||
        $_POST['limite_credito'] === '' ||
        $_POST['saldo_credito'] === ''
    )
    {
        redirigirConMensaje("Complete correctamente todos los campos obligatorios.", "danger", "../nuevoCliente.php");
    }


    if ($limite_credito < 0 || $saldo_credito < 0){
        redirigirConMensaje("El límite de crédito y el saldo de crédito no deben ser negativos.", "danger", "../nuevoCliente.php");
    }

    if ($estado != 0 && $estado != 1) {
        redirigirConMensaje("El estado seleccionado no es válido.", "danger", "../nuevoCliente.php");
    }

    if(!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)){
        redirigirConMensaje("El correo electrónico no es válido.", "danger", "../nuevoCliente.php");
    }

    if (!empty($identidad)) {
        if (strlen($identidad) !== 13 || !ctype_digit($identidad)) {
            redirigirConMensaje("La identidad debe tener exactamente 13 dígitos y contener solo números.", "danger", "../nuevoCliente.php");
        }

        $consulta = "SELECT id_cliente FROM clientes WHERE identidad = ?";
        $stmt = $conn->prepare($consulta);
        $stmt->bind_param("s", $identidad);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            redirigirConMensaje("El cliente con esa identidad ya existe.", "danger", "../nuevoCliente.php");
        }
    } else {
        $identidad = null;
    }


    $query = "INSERT INTO clientes (nombre, identidad, telefono, correo, direccion, limite_credito, saldo_credito, estado) VALUES
    (?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssddi", $nombre, $identidad, $telefono, $correo, $direccion, $limite_credito, $saldo_credito, $estado);


    if ($stmt->execute()){
        redirigirConMensaje("Cliente registrado correctamente.", "success", "../clientes.php");
    } else {
        redirigirConMensaje("Error al registrar el cliente.", "danger", "../nuevoCliente.php");
    }

?>
