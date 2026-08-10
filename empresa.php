<?php

require "includes/session.php";   // Verifica que el usuario esté logueado
require "config/db.php";          // Conexión a la base

// Obtenemos los datos actuales de la empresa
$query = "SELECT * FROM empresa WHERE id_empresa = 1 LIMIT 1";
$resultado = $conn->query($query);
$empresa = $resultado->fetch_assoc();

require "includes/header.php";
require "includes/navbar.php";
require "includes/sidebar.php";
?>

<div class="content">
    <div class="container-fluid mt-4">
        <h3>Datos de la Empresa</h3>
        <p class="text-muted">Aquí se configuran los datos que aparecerán en facturas y reportes.</p>

        <?php
        // Mostramos mensajes de sesión si existen
        if (isset($_SESSION['mensaje'])) {
            $tipo = $_SESSION['tipo'] ?? 'info';
            ?>
            <div class="alert alert-<?php echo $tipo; ?> alert-dismissible fade show">
                <?php echo $_SESSION['mensaje']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['tipo']);
        }
        ?>

        <div class="card">
            <div class="card-body">
                <form action="controllers/empresa.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la empresa</label>
                        <input type="text"
                               name="nombre"
                               class="form-control"
                               required
                               value="<?php echo htmlspecialchars($empresa['nombre'] ?? ''); ?>">
                    </div>

                    <!-- Nombre comercial -->
                    <div class="mb-3">
                        <label class="form-label">Nombre comercial</label>
                        <input type="text"
                               name="nombre_comercial"
                               class="form-control"
                               value="<?php echo htmlspecialchars($empresa['nombre_comercial'] ?? ''); ?>">
                    </div>

                    <!-- Razón social -->
                    <div class="mb-3">
                        <label class="form-label">Razón social</label>
                        <input type="text"
                               name="razon_social"
                               class="form-control"
                               value="<?php echo htmlspecialchars($empresa['razon_social'] ?? ''); ?>">
                    </div>

                    <!-- RTN / NIF -->
                    <div class="mb-3">
                        <label class="form-label">RTN / NIF</label>
                        <input type="text"
                               name="rtn"
                               class="form-control"
                               value="<?php echo htmlspecialchars($empresa['rtn'] ?? ''); ?>">
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text"
                               name="telefono"
                               class="form-control"
                               value="<?php echo htmlspecialchars($empresa['telefono'] ?? ''); ?>">
                    </div>

                    <!-- Correo -->
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email"
                               name="correo"
                               class="form-control"
                               value="<?php echo htmlspecialchars($empresa['correo'] ?? ''); ?>">
                    </div>

                    <!-- Departamento y municipio -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Departamento</label>
                            <input type="text"
                                   name="departamento"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($empresa['departamento'] ?? ''); ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Municipio</label>
                            <input type="text"
                                   name="municipio"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($empresa['municipio'] ?? ''); ?>">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea name="direccion"
                                  class="form-control"
                                  rows="3"><?php echo htmlspecialchars($empresa['direccion'] ?? ''); ?></textarea>
                    </div>

                    <!-- Logo -->
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file"
                               name="logo"
                               class="form-control"
                               accept=".jpg,.jpeg,.png,.webp">
                        <input type="hidden"
                               name="logo_actual"
                               value="<?php echo htmlspecialchars($empresa['logo'] ?? ''); ?>">

                        <?php if (!empty($empresa['logo'])) { ?>
                            <img src="<?php echo htmlspecialchars($empresa['logo']); ?>"
                                 alt="Logo de la empresa"
                                 class="mt-2"
                                 style="max-width: 150px; max-height: 100px;">
                        <?php } ?>
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-control">
                            <option value="A" <?php if (($empresa['estado'] ?? 'A') == 'A') echo 'selected'; ?>>Activa</option>
                            <option value="I" <?php if (($empresa['estado'] ?? 'A') == 'I') echo 'selected'; ?>>Inactiva</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save"></i> Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php
require "includes/footer.php";
$conn->close();
?>
