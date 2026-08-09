<?php

session_start();

require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $nombre      = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $estado      = intval($_POST['estado']);

    // Validar campos obligatorios

    if (empty($nombre)) {

        $_SESSION['mensaje'] = "Complete todos los campos obligatorios.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../nuevoRol.php");
        exit;
    }

    // Verificar rol existente

    $consulta = "SELECT id_rol FROM roles WHERE nombre = ?";

    $stmt = $conn->prepare($consulta);

    $stmt->bind_param("s", $nombre);

    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $_SESSION['mensaje'] = "El rol ya existe.";
        $_SESSION['tipo'] = "warning";

        header("Location: ../nuevoRol.php");
        exit;
    }

    // Insertar rol

    $query = "
        INSERT INTO roles
        (nombre, descripcion, estado)
        VALUES
        (?, ?, ?)
    ";

    $stmt = $conn->prepare($query);

    $stmt->bind_param(
        "ssi",
        $nombre,
        $descripcion,
        $estado
    );

    if ($stmt->execute()) {

        $_SESSION['mensaje'] = "Rol registrado correctamente.";
        $_SESSION['tipo'] = "success";

        header("Location: ../roles.php");
        exit;
    } else {

        $_SESSION['mensaje'] = "Error al guardar el rol.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../nuevoRol.php");
        exit;
    }
} else {

    header("Location: ../roles.php");
    exit;
}

$conn->close();