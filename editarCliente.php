<?php

require "includes/session.php";
require "config/db.php";

if (!isset($_GET['id'])) {
    header("Location: clientes.php");
    exit;
}

$id_cliente = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    header("Location: clientes.php");
    exit;
}

$cliente = $resultado->fetch_assoc();

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-user-pen"></i>
            Editar Cliente
        </h2>
        <a href="clientes.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i>
            Regresar
        </a>
    </div>

    <?php if (isset($_SESSION['mensaje'])) { ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['tipo']); ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
    <?php
        unset($_SESSION['mensaje'], $_SESSION['tipo']);
    }
    ?>

    <div class="card">
        <div class="card-body">
            <form action="controllers/editarCliente.php" method="POST">
                <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" id="nombre" class="form-control" name="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="identidad" class="form-label">Identidad</label>
                        <input type="text" id="identidad" class="form-control" name="identidad" value="<?php echo htmlspecialchars($cliente['identidad']); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" class="form-control" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" id="correo" class="form-control" name="correo" value="<?php echo htmlspecialchars($cliente['correo']); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea id="direccion" class="form-control" name="direccion" rows="3"><?php echo htmlspecialchars($cliente['direccion']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="limite_credito" class="form-label">Límite de crédito</label>
                        <input type="number" id="limite_credito" class="form-control" name="limite_credito" min="0" step="0.01" value="<?php echo $cliente['limite_credito']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="saldo_credito" class="form-label">Saldo de crédito</label>
                        <input type="number" id="saldo_credito" class="form-control" name="saldo_credito" min="0" step="0.01" value="<?php echo $cliente['saldo_credito']; ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-control">
                        <option value="1" <?php if ($cliente['estado'] == 1) echo "selected"; ?>>Activo</option>
                        <option value="0" <?php if ($cliente['estado'] == 0) echo "selected"; ?>>Inactivo</option>
                    </select>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Actualizar Cliente
                </button>
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
