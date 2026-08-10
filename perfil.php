<?php

require "includes/session.php";
require "config/db.php";

$id_usuario = $_SESSION['id_usuario'];

$query = "SELECT
                u.id_usuario,
                u.nombre,
                u.usuario,
                u.correo,
                u.estado,
                u.fecha_creacion,
                u.ultimo_acceso,
                r.nombre AS nombre_rol
          FROM usuarios u
          INNER JOIN roles r
              ON u.id_rol = r.id_rol
          WHERE u.id_usuario = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    header("Location: dashboard.php");
    exit;
}

$perfil = $resultado->fetch_assoc();

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-id-card"></i>
            Mi Perfil
        </h2>
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i>
            Regresar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fa-solid fa-circle-user" style="font-size: 90px; color: #0d6efd;"></i>
                    <h4 class="mt-3 mb-0"><?php echo htmlspecialchars($perfil['nombre']); ?></h4>
                    <p class="text-muted mb-2">@<?php echo htmlspecialchars($perfil['usuario']); ?></p>
                    <span class="badge bg-primary"><?php echo htmlspecialchars($perfil['nombre_rol']); ?></span>

                    <hr>

                    <div class="text-start">
                        <p class="mb-2">
                            <i class="fa-solid fa-envelope text-muted"></i>
                            <strong>Correo:</strong>
                            <?php echo htmlspecialchars($perfil['correo'] ?: 'No registrado'); ?>
                        </p>
                        <p class="mb-2">
                            <i class="fa-solid fa-circle-check text-muted"></i>
                            <strong>Estado:</strong>
                            <?php if ($perfil['estado'] == 1) { ?>
                                <span class="badge bg-success">Activo</span>
                            <?php } else { ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php } ?>
                        </p>
                        <p class="mb-2">
                            <i class="fa-solid fa-calendar-days text-muted"></i>
                            <strong>Miembro desde:</strong>
                            <?php echo date("d/m/Y", strtotime($perfil['fecha_creacion'])); ?>
                        </p>
                        <?php if (!empty($perfil['ultimo_acceso'])) { ?>
                            <p class="mb-0">
                                <i class="fa-solid fa-clock text-muted"></i>
                                <strong>Último acceso:</strong>
                                <?php echo date("d/m/Y h:i A", strtotime($perfil['ultimo_acceso'])); ?>
                            </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
