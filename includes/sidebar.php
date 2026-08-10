<?php require_once __DIR__ . "/permisos.php"; ?>

<div class="sidebar">

    <div class="sidebar-header">
        <h4>
            <i class="fa-solid fa-cash-register"></i>
            Punto de Venta
        </h4>
    </div>

    <div class="usuario">
        <div class="avatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div>
            <strong><?php echo $_SESSION['nombre']; ?></strong>
            <br>
            <small><?php echo $_SESSION['nombre_rol']; ?></small>
        </div>
    </div>

    <ul class="menu">

        <!-- Dashboard (todos lo ven) -->
        <li>
            <a href="dashboard.php">
                <i class="fa-solid fa-gauge"></i>
                Dashboard
            </a>
        </li>

        <?php if (tienePermiso($conn, 'usuarios_ver') || tienePermiso($conn, 'roles_ver')): ?>
            <li class="titulo">ADMINISTRACIÓN</li>

            <?php if (tienePermiso($conn, 'usuarios_ver')): ?>
            <li>
                <a href="usuarios.php">
                    <i class="fa-solid fa-users"></i>
                    Usuarios
                </a>
            </li>
            <?php endif; ?>

            <?php if (tienePermiso($conn, 'roles_ver')): ?>
            <li>
                <a href="roles.php">
                    <i class="fa-solid fa-user-shield"></i>
                    Roles
                </a>
            </li>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (
            tienePermiso($conn, 'categorias_ver') ||
            tienePermiso($conn, 'productos_ver') ||
            tienePermiso($conn, 'clientes_ver') ||
            tienePermiso($conn, 'proveedores_ver')
        ): ?>
            <li class="titulo">INVENTARIO</li>

            <?php if (tienePermiso($conn, 'categorias_ver')): ?>
            <li>
                <a href="categorias.php">
                    <i class="fa-solid fa-tags"></i>
                    Categorías
                </a>
            </li>
            <?php endif; ?>

            <?php if (tienePermiso($conn, 'productos_ver')): ?>
            <li>
                <a href="productos.php">
                    <i class="fa-solid fa-box"></i>
                    Productos
                </a>
            </li>
            <?php endif; ?>

            <?php if (tienePermiso($conn, 'clientes_ver')): ?>
            <li>
                <a href="clientes.php">
                    <i class="fa-solid fa-address-book"></i>
                    Clientes
                </a>
            </li>
            <?php endif; ?>

            <?php if (tienePermiso($conn, 'proveedores_ver')): ?>
            <li>
                <a href="proveedores.php">
                    <i class="fa-solid fa-truck"></i>
                    Proveedores
                </a>
            </li>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (tienePermiso($conn, 'ventas_crear') || tienePermiso($conn, 'ventas_ver')): ?>
            <li class="titulo">VENTAS</li>

            <?php if (tienePermiso($conn, 'ventas_crear')): ?>
            <li>
                <a href="ventas.php">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Nueva Venta
                </a>
            </li>
            <?php endif; ?>

            <?php if (tienePermiso($conn, 'ventas_ver')): ?>
            <li>
                <a href="facturas.php">
                    <i class="fa-solid fa-file-invoice"></i>
                    Facturas
                </a>
            </li>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (
            tienePermiso($conn, 'caja_abrir') ||
            tienePermiso($conn, 'caja_cerrar') ||
            tienePermiso($conn, 'caja_movimientos')
        ): ?>
            <li class="titulo">CAJA</li>

            <?php if (tienePermiso($conn, 'caja_abrir')): ?>
            <li>
                <a href="aperturaCaja.php">
                    <i class="fa-solid fa-lock-open"></i>
                    Apertura de Caja
                </a>
            </li>
            <?php endif; ?>

            <?php if (tienePermiso($conn, 'caja_cerrar')): ?>
            <li>
                <a href="cierresCaja.php">
                    <i class="fa-solid fa-lock"></i>
                    Cierre de Caja
                </a>
            </li>
            <?php endif; ?>

            <?php if (tienePermiso($conn, 'caja_movimientos')): ?>
            <li>
                <a href="movimientosCaja.php">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                    Movimientos
                </a>
            </li>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (tienePermiso($conn, 'reportes_ver')): ?>
            <li class="titulo">REPORTES</li>

            <li>
                <a href="reportes.php">
                    <i class="fa-solid fa-chart-column"></i>
                    Reportes
                </a>
            </li>
        <?php endif; ?>

        <?php if (tienePermiso($conn, 'empresa_ver')): ?>
            <li class="titulo">CONFIGURACIÓN</li>

            <li>
                <a href="empresa.php">
                    <i class="fa-solid fa-building"></i>
                    Empresa
                </a>
            </li>
        <?php endif; ?>

        <li>
            <a href="logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                Cerrar Sesión
            </a>
        </li>

    </ul>

</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
