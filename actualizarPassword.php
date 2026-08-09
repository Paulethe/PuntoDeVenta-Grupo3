<?php

session_start();

require "../config/db.php";

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $id_usuario = intval($_SESSION['id_usuario']);

    $password_actual    = trim($_POST['password_actual']);
    $password_nueva     = trim($_POST['password_nueva']);
    $password_confirmar = trim($_POST['password_confirmar']);

    if (empty($password_actual) || empty($password_nueva) || empty($password_confirmar)) {

        $_SESSION['mensaje'] = "Complete todos los campos.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../cambiarPassword.php");
        exit;
    }

    if (strlen($password_nueva) < 8) {

        $_SESSION['mensaje'] = "La nueva contraseña debe tener al menos 8 caracteres.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../cambiarPassword.php");
        exit;
    }

    if ($password_nueva !== $password_confirmar) {

        $_SESSION['mensaje'] = "La nueva contraseña y su confirmación no coinciden.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../cambiarPassword.php");
        exit;
    }

    // Obtener el hash actual guardado para verificar la contraseña actual

    $query = "SELECT password FROM usuarios WHERE id_usuario = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {

        $_SESSION['mensaje'] = "No se encontró el usuario.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../cambiarPassword.php");
        exit;
    }

    $datos = $resultado->fetch_assoc();

    if (!password_verify($password_actual, $datos['password'])) {

        $_SESSION['mensaje'] = "La contraseña actual es incorrecta.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../cambiarPassword.php");
        exit;
    }

    // No permitir que la nueva contraseña sea igual a la actual

    if (password_verify($password_nueva, $datos['password'])) {

        $_SESSION['mensaje'] = "La nueva contraseña debe ser diferente a la actual.";
        $_SESSION['tipo'] = "warning";

        header("Location: ../cambiarPassword.php");
        exit;
    }

    $passwordHash = password_hash($password_nueva, PASSWORD_DEFAULT);

    $query = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $passwordHash, $id_usuario);

    if ($stmt->execute()) {

        $_SESSION['mensaje'] = "Contraseña actualizada correctamente.";
        $_SESSION['tipo'] = "success";
    } else {

        $_SESSION['mensaje'] = "Error al actualizar la contraseña.";
        $_SESSION['tipo'] = "danger";
    }

    header("Location: ../perfil.php");
    exit;
}

header("Location: ../cambiarPassword.php");
exit;
