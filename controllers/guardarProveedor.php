<?php

session_start();

require "../config/db.php";
require_once "../includes/validarController.php";
validarControlador($conn, "proveedores_crear");

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $nombre    = trim($_POST['nombre']);
    $empresa   = trim($_POST['empresa']);
    $identidad = trim($_POST['identidad']);
    $telefono  = trim($_POST['telefono']);
    $correo    = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);
    $estado    = intval($_POST['estado']);

    // Validar campos obligatorios

    if (empty($nombre)) {

        $_SESSION['mensaje'] = "Complete todos los campos obligatorios.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../nuevoProveedor.php");
        exit;
    }

    // Verificar identidad existente (si se proporcionó)

    if (!empty($identidad)) {

        $consulta = "SELECT id_proveedor FROM proveedores WHERE identidad = ?";

        $stmt = $conn->prepare($consulta);

        $stmt->bind_param("s", $identidad);

        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $_SESSION['mensaje'] = "Ya existe un proveedor con esa identidad.";
            $_SESSION['tipo'] = "warning";

            header("Location: ../nuevoProveedor.php");
            exit;
        }
    }

    // Insertar proveedor

    $query = "
        INSERT INTO proveedores
        (nombre, empresa, identidad, telefono, correo, direccion, estado)
        VALUES
        (?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($query);

    $stmt->bind_param(
        "ssssssi",
        $nombre,
        $empresa,
        $identidad,
        $telefono,
        $correo,
        $direccion,
        $estado
    );

    if ($stmt->execute()) {

        $_SESSION['mensaje'] = "Proveedor registrado correctamente.";
        $_SESSION['tipo'] = "success";

        header("Location: ../proveedores.php");
        exit;
    } else {

        $_SESSION['mensaje'] = "Error al guardar el proveedor.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../nuevoProveedor.php");
        exit;
    }
} else {

    header("Location: ../proveedores.php");
    exit;
}

$conn->close();
