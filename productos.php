<?php

require "includes/session.php";
require "config/db.php";

$query = "
    SELECT
        p.id_producto,
        p.codigo,
        p.codigo_barras,
        p.nombre,
        p.descripcion,
        c.categoria AS categoria_nombre,
        p.precio_costo,
        p.precio_venta,
        p.stock,
        p.stock_minimo,
        p.stock_maximo,
        p.unidad_medida,
        p.tipo,
        p.imagen,
        p.estado,
        p.fecha_creacion,
        p.fecha_actualizacion
    FROM productos p
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    ORDER BY p.id_producto DESC
";

$resultado = $conn->query($query);

include "includes/header.php";
include "includes/sidebar.php";
include "includes/navbar.php";

?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-box"></i>
            Productos
        </h2>

        <a class="btn btn-primary" href="nuevoProducto.php">
            <i class="fa-solid fa-box-open"></i>
            Nuevo Producto
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Código de barras</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Precio costo</th>
                            <th>Precio venta</th>
                            <th>Stock</th>
                            <th>Unidad</th>
                            <th>Tipo</th>
                            <th>Imagen</th>
                            <th>Estado</th>
                            <th>Fecha creación</th>
                            <th>Fecha actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($producto = $resultado->fetch_assoc()) {
                            $rutaImagen = $producto['imagen'] ?? '';
                            $archivoImagen = __DIR__ . '/' . $rutaImagen;
                        ?>
                            <tr>
                                <td><?php echo $producto['id_producto']; ?></td>
                                <td><?php echo htmlspecialchars($producto['codigo']); ?></td>
                                <td><?php echo htmlspecialchars($producto['codigo_barras']); ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
                                <td><?php echo number_format((float) $producto['precio_costo'], 2); ?></td>
                                <td><?php echo number_format((float) $producto['precio_venta'], 2); ?></td>
                                <td><?php echo $producto['stock']; ?></td>
                                <td><?php echo htmlspecialchars($producto['unidad_medida']); ?></td>
                                <td><?php echo htmlspecialchars($producto['tipo']); ?></td>
                                <td>
                                    <?php if ($rutaImagen !== '' && file_exists($archivoImagen)) { ?>
                                        <img src="<?php echo htmlspecialchars($rutaImagen); ?>"
                                            alt="Imagen de <?php echo htmlspecialchars($producto['nombre']); ?>"
                                            width="70" height="70" style="object-fit: cover;">
                                    <?php } else { ?>
                                        <span class="text-muted">Sin imagen</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($producto['estado'] == 1) { ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $producto['fecha_creacion']; ?></td>
                                <td><?php echo $producto['fecha_actualizacion'] ?? ''; ?></td>
                                
                                <td class="text-nowrap">
                                    <a href="editarProducto.php?id=<?php echo $producto['id_producto']; ?>"
                                        class="btn btn-primary btn-sm" title="Editar producto">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <?php if ($producto['estado'] == 1) { ?>
                                        <a href="controllers/eliminarProducto.php?id=<?php echo $producto['id_producto']; ?>"
                                            class="btn btn-danger btn-sm" title="Eliminar producto"
                                            onclick="return confirm('¿Desea desactivar este producto?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
