USE punto_venta;

-- Agrega los permisos que todavía no existan en una base ya instalada.
INSERT IGNORE INTO permisos (nombre, descripcion) VALUES
('roles_ver', 'Ver roles'),
('roles_crear', 'Crear roles'),
('roles_editar', 'Editar roles'),
('roles_eliminar', 'Eliminar roles'),
('categorias_ver', 'Ver categorías'),
('categorias_crear', 'Crear categorías'),
('categorias_editar', 'Editar categorías'),
('categorias_eliminar', 'Eliminar categorías'),
('proveedores_ver', 'Ver proveedores'),
('proveedores_crear', 'Crear proveedores'),
('proveedores_editar', 'Editar proveedores'),
('proveedores_eliminar', 'Eliminar proveedores'),
('caja_movimientos', 'Registrar movimientos de caja'),
('empresa_ver', 'Ver datos de la empresa'),
('empresa_editar', 'Editar datos de la empresa');

-- El Administrador recibe todos los permisos.
INSERT IGNORE INTO roles_permisos (id_rol, id_permiso)
SELECT r.id_rol, p.id_permiso
FROM roles r
CROSS JOIN permisos p
WHERE r.nombre = 'Administrador';

-- Permisos del Cajero.
INSERT IGNORE INTO roles_permisos (id_rol, id_permiso)
SELECT r.id_rol, p.id_permiso
FROM roles r
INNER JOIN permisos p
    ON p.nombre IN (
        'clientes_ver', 'clientes_crear',
        'ventas_ver', 'ventas_crear',
        'caja_ver', 'caja_abrir', 'caja_cerrar', 'caja_movimientos'
    )
WHERE r.nombre = 'Cajero';

-- Permisos del Supervisor.
INSERT IGNORE INTO roles_permisos (id_rol, id_permiso)
SELECT r.id_rol, p.id_permiso
FROM roles r
INNER JOIN permisos p
    ON p.nombre IN (
        'categorias_ver', 'categorias_crear', 'categorias_editar', 'categorias_eliminar',
        'productos_ver', 'productos_crear', 'productos_editar', 'productos_eliminar',
        'clientes_ver', 'clientes_crear', 'clientes_editar', 'clientes_eliminar',
        'proveedores_ver', 'proveedores_crear', 'proveedores_editar', 'proveedores_eliminar',
        'ventas_ver', 'ventas_crear', 'ventas_anular',
        'reportes_ver', 'reportes_exportar'
    )
WHERE r.nombre = 'Supervisor';
