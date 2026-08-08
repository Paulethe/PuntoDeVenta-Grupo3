<?php

session_start();

require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $id_proveedor = intval($_POST['id_proveedor']);
    $nombre       = trim($_POST['nombre']);
    $empresa      = trim($_POST['empresa']);
    $identidad    = trim($_POST['identidad']);
    $telefono     = trim($_POST['telefono']);
    $correo       = trim($_POST['correo']);
    $direccion    = trim($_POST['direccion']);
    $estado       = intval($_POST['estado']);

    if (empty($id_proveedor) || empty($nombre)) {

        $_SESSION['mensaje'] = "Complete todos los campos obligatorios.";
        $_SESSION['tipo'] = "danger";

        header("Location: ../editarProveedor.php?id=" . $id_proveedor);
        exit;
    }

    // Verificar que la identidad no exista en otro registro

    if (!empty($identidad)) {

        $query = "SELECT id_proveedor
                  FROM proveedores
                  WHERE identidad = ?
                  AND id_proveedor <> ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("si", $identidad, $id_proveedor);

        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $_SESSION['mensaje'] = "Ya existe otro proveedor con esa identidad.";
            $_SESSION['tipo'] = "warning";

            header("Location: ../editarProveedor.php?id=" . $id_proveedor);
            exit;
        }
    }

    $query = "UPDATE proveedores
              SET
                nombre=?,
                empresa=?,
                identidad=?,
                telefono=?,
                correo=?,
                direccion=?,
                estado=?
              WHERE id_proveedor=?";

    $stmt = $conn->prepare($query);

    $stmt->bind_param(
        "ssssssii",
        $nombre,
        $empresa,
        $identidad,
        $telefono,
        $correo,
        $direccion,
        $estado,
        $id_proveedor
    );

    if ($stmt->execute()) {

        $_SESSION['mensaje'] = "Proveedor actualizado correctamente.";
        $_SESSION['tipo'] = "success";
    } else {

        $_SESSION['mensaje'] = "Error al actualizar el proveedor.";
        $_SESSION['tipo'] = "danger";
    }

    header("Location: ../proveedores.php");
    exit;
}

header("Location: ../proveedores.php");
exit;
