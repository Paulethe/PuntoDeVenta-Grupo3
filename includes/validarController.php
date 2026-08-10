<?php

require_once __DIR__ . "/permisos.php";

function validarControlador($conn, $permiso)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    validarSesion("../login.php");
    requerirPermiso($conn, $permiso, "../dashboard.php");
}

