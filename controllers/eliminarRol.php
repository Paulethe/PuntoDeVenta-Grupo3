<?php

session_start();

require "../config/db.php";

if (!isset($_GET['id'])) {

    header("Location: ../roles.php");
    exit;
}

$id_rol = intval($_GET['id']);

// Validar que exista

$query = "SELECT id_rol
          FROM roles
          WHERE id_rol = ?";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id_rol);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {

    $_SESSION['mensaje'] = "El rol no existe.";
    $_SESSION['tipo'] = "warning";

    header("Location: ../roles.php");
    exit;
}

// Cambiar estado

$query = "UPDATE roles
          SET estado = 0
          WHERE id_rol = ?";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id_rol);

if ($stmt->execute()) {

    $_SESSION['mensaje'] = "Rol desactivado correctamente.";
    $_SESSION['tipo'] = "success";
} else {

    $_SESSION['mensaje'] = "Error al desactivar el rol.";
    $_SESSION['tipo'] = "danger";
}

header("Location: ../roles.php");
exit;

?>