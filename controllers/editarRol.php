<?php

session_start();

require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $id_rol      = intval($_POST['id_rol']);
    $nombre      = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $estado      = intval($_POST['estado']);

    if (empty($id_rol) || empty($nombre)) {

        $_SESSION['mensaje'] = "Complete todos los campos obligatorios.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../editarRol.php?id=" . $id_rol);
        exit;
    }

    // Verificar que el rol no exista en otro registro

    $query = "SELECT id_rol
              FROM roles
              WHERE nombre = ?
              AND id_rol <> ?";

    $stmt = $conn->prepare($query);

    $stmt->bind_param("si", $nombre, $id_rol);

    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $_SESSION['mensaje'] = "Ya existe otro rol con ese nombre.";
        $_SESSION['tipo'] = "warning";

        header("Location: ../editarRol.php?id=" . $id_rol);
        exit;
    }

    $query = "UPDATE roles
              SET
                nombre=?,
                descripcion=?,
                estado=?
              WHERE id_rol=?";

    $stmt = $conn->prepare($query);

    $stmt->bind_param(
        "ssii",
        $nombre,
        $descripcion,
        $estado,
        $id_rol
    );

    if ($stmt->execute()) {

        $_SESSION['mensaje'] = "Rol actualizado correctamente.";
        $_SESSION['tipo'] = "success";
    } else {

        $_SESSION['mensaje'] = "Error al actualizar el rol.";
        $_SESSION['tipo'] = "danger";
    }

    header("Location: ../roles.php");
    exit;
}

header("Location: ../roles.php");
exit;