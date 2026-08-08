<?php

session_start();

require "../config/db.php";

if (!isset($_GET['id'])) {

    header("Location: ../categorias.php");
    exit;
}

$id_categoria = intval($_GET['id']);

// Validar que exista

$query = "SELECT id_categoria
          FROM categorias
          WHERE id_categoria = ?";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id_categoria);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {

    $_SESSION['mensaje'] = "La categoría no existe.";
    $_SESSION['tipo'] = "warning";

    header("Location: ../categorias.php");
    exit;
}

// Cambiar estado

$query = "UPDATE categorias
          SET estado = 0
          WHERE id_categoria = ?";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id_categoria);

if ($stmt->execute()) {

    $_SESSION['mensaje'] = "Categoría desactivada correctamente.";
    $_SESSION['tipo'] = "success";
} else {

    $_SESSION['mensaje'] = "Error al desactivar la categoría.";
    $_SESSION['tipo'] = "danger";
}

header("Location: ../categorias.php");
exit;

?>
