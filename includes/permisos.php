<?php

function validarSesion($destinoLogin)
{
    if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
        header("Location: " . $destinoLogin);
        exit;
    }
}

function obtenerPermisos($conn)
{
    static $permisosPorRol = [];

    $id_rol = intval($_SESSION['id_rol'] ?? 0);

    if ($id_rol <= 0) {
        return [];
    }

    if (isset($permisosPorRol[$id_rol])) {
        return $permisosPorRol[$id_rol];
    }

    $query = "SELECT p.nombre
              FROM roles_permisos rp
              INNER JOIN permisos p ON p.id_permiso = rp.id_permiso
              INNER JOIN roles r ON r.id_rol = rp.id_rol
              WHERE rp.id_rol = ?
              AND p.estado = 1
              AND r.estado = 1";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_rol);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $permisos = [];

    while ($fila = $resultado->fetch_assoc()) {
        $permisos[] = $fila['nombre'];
    }

    $stmt->close();
    $permisosPorRol[$id_rol] = $permisos;

    return $permisos;
}

function tienePermiso($conn, $permiso)
{
    return in_array($permiso, obtenerPermisos($conn), true);
}

function requerirPermiso($conn, $permiso, $destino)
{
    if (!tienePermiso($conn, $permiso)) {
        $_SESSION['mensaje'] = "No tiene permiso para realizar esta acción.";
        $_SESSION['tipo'] = "warning";
        header("Location: " . $destino);
        exit;
    }
}

