<?php

require "includes/session.php";
require "config/db.php";

if (!isset($_GET['id'])) {
    header("Location: productos.php");
    exit;
}

$id_producto = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    header("Location: productos.php");
    exit;
}

$producto = $resultado->fetch_assoc();
$categorias = $conn->query("SELECT id_categoria, categoria FROM categorias WHERE estado = 1 ORDER BY categoria ASC");

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-box"></i>
            Editar Producto
        </h2>
        <a href="productos.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i>
            Regresar
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="controllers/editarProducto.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" id="codigo" class="form-control" name="codigo" value="<?php echo htmlspecialchars($producto['codigo']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_barras" class="form-label">Código de barras</label>
                        <input type="text" id="codigo_barras" class="form-control" name="codigo_barras" value="<?php echo htmlspecialchars($producto['codigo_barras']); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" class="form-control" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="id_categoria" class="form-label">Categoría</label>
                        <select id="id_categoria" name="id_categoria" class="form-control select2" required>
                            <?php while ($categoria = $categorias->fetch_assoc()) { ?>
                                <option value="<?php echo $categoria['id_categoria']; ?>" <?php if ($categoria['id_categoria'] == $producto['id_categoria']) echo "selected"; ?>>
                                    <?php echo htmlspecialchars($categoria['categoria']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" class="form-control" name="descripcion" rows="3"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="precio_costo" class="form-label">Precio costo</label>
                        <input type="number" id="precio_costo" class="form-control" name="precio_costo" min="0" step="0.01" value="<?php echo $producto['precio_costo']; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="precio_venta" class="form-label">Precio venta</label>
                        <input type="number" id="precio_venta" class="form-control" name="precio_venta" min="0" step="0.01" value="<?php echo $producto['precio_venta']; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="unidad_medida" class="form-label">Unidad de medida</label>
                        <input type="text" id="unidad_medida" class="form-control" name="unidad_medida" value="<?php echo htmlspecialchars($producto['unidad_medida']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" id="stock" class="form-control" name="stock" min="0" step="0.01" value="<?php echo $producto['stock']; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stock_minimo" class="form-label">Stock mínimo</label>
                        <input type="number" id="stock_minimo" class="form-control" name="stock_minimo" min="0" step="0.01" value="<?php echo $producto['stock_minimo']; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stock_maximo" class="form-label">Stock máximo</label>
                        <input type="number" id="stock_maximo" class="form-control" name="stock_maximo" min="0" step="0.01" value="<?php echo $producto['stock_maximo'] ?? ''; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select id="tipo" name="tipo" class="form-control" required>
                            <option value="Producto" <?php if ($producto['tipo'] == 'Producto') echo "selected"; ?>>Producto</option>
                            <option value="Servicio" <?php if ($producto['tipo'] == 'Servicio') echo "selected"; ?>>Servicio</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" id="imagen" class="form-control" name="imagen" accept="image/*">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-control">
                        <option value="1" <?php if ($producto['estado'] == 1) echo "selected"; ?>>Activo</option>
                        <option value="0" <?php if ($producto['estado'] == 0) echo "selected"; ?>>Inactivo</option>
                    </select>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Actualizar Producto
                </button>
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
