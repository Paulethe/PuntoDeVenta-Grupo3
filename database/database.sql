CREATE DATABASE IF NOT EXISTS punto_venta
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

use punto_venta;

CREATE TABLE roles (
    id_rol int AUTO_INCREMENT PRIMARY key,
    nombre varchar(100) not null UNIQUE,
    descripcion varchar(255),
    estado tinyint(1) not null default 1,
    fecha_creacion datetime not null DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE permisos (
    id_permiso int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(100) not null UNIQUE,
    descripcion varchar(255),
    estado tinyint(1) not null default 1,
    fecha_creacion datetime not null DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE roles_permisos (
    id_rol int not null,
    id_permiso int not null,
    PRIMARY KEY(id_rol, id_permiso),
    
    CONSTRAINT fk_roles_permisos_rol
    FOREIGN KEY (id_rol)
    REFERENCES roles(id_rol)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    CONSTRAINT fk_roles_permisos_permiso
    FOREIGN KEY (id_permiso)
    REFERENCES permisos(id_permiso)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(150) NOT NULL,

    usuario VARCHAR(100) NOT NULL UNIQUE,

    correo VARCHAR(150) UNIQUE,

    password VARCHAR(255) NOT NULL,

    id_rol INT NOT NULL,

    estado TINYINT(1) NOT NULL DEFAULT 1,

    ultimo_acceso DATETIME NULL,

    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    fecha_actualizacion DATETIME NULL
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_usuarios_roles
        FOREIGN KEY (id_rol)
        REFERENCES roles(id_rol)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(150) not null unique,
    descripcion varchar(255),
    estado tinyint(1) not null DEFAULT 1,
    fecha_creacion datetime not null default CURRENT_TIMESTAMP,
    fecha_actualizacion datetime null
    on UPDATE CURRENT_TIMESTAMP
    );

CREATE TABLE proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    empresa VARCHAR(200),
    identidad VARCHAR(50) UNIQUE,
    telefono VARCHAR(50),
    correo VARCHAR(150),
    direccion TEXT,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NULL
        ON UPDATE CURRENT_TIMESTAMP
);
    
    CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,

    codigo VARCHAR(100) UNIQUE,

    codigo_barras VARCHAR(100) UNIQUE,

    nombre VARCHAR(200) NOT NULL,

    descripcion TEXT,

    id_categoria INT NOT NULL,

    precio_costo DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    precio_venta DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    stock DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    stock_minimo DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    stock_maximo DECIMAL(12,2) NULL,

    unidad_medida VARCHAR(50) NOT NULL DEFAULT 'Unidad',

    tipo ENUM('Producto','Servicio') NOT NULL DEFAULT 'Producto',

    imagen VARCHAR(255) NULL,

    estado TINYINT(1) NOT NULL DEFAULT 1,

    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    fecha_actualizacion DATETIME NULL
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_productos_categorias
        FOREIGN KEY (id_categoria)
        REFERENCES categorias(id_categoria)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE clientes (
    id_cliente int AUTO_INCREMENT PRIMARY key,
    nombre varchar(200) not null,
    identidad varchar(50) UNIQUE,
    telefono varchar(50),
    correo varchar(50),
    direccion text,
    limite_credito decimal(12,2) not null DEFAULT 0.00,
    saldo_credito decimal(12,2) not null DEFAULT 0.00,
    estado tinyint(1) not null DEFAULT 1,
    fecha_creacion datetime not null DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NULL
            ON UPDATE CURRENT_TIMESTAMP

    );
    
-- =====================================================
-- DATOS INICIALES: ROLES
-- =====================================================

INSERT INTO roles
(nombre, descripcion)
VALUES
('Administrador', 'Acceso total al sistema'),
('Cajero', 'Realiza ventas y operaciones de caja'),
('Supervisor', 'Supervisa operaciones y reportes');


-- =====================================================
-- DATOS INICIALES: PERMISOS
-- =====================================================

INSERT INTO permisos
(nombre, descripcion)
VALUES
('usuarios_ver', 'Ver usuarios'),
('usuarios_crear', 'Crear usuarios'),
('usuarios_editar', 'Editar usuarios'),
('usuarios_eliminar', 'Eliminar usuarios'),

('roles_ver', 'Ver roles'),
('roles_crear', 'Crear roles'),
('roles_editar', 'Editar roles'),
('roles_eliminar', 'Eliminar roles'),

('categorias_ver', 'Ver categorías'),
('categorias_crear', 'Crear categorías'),
('categorias_editar', 'Editar categorías'),
('categorias_eliminar', 'Eliminar categorías'),

('productos_ver', 'Ver productos'),
('productos_crear', 'Crear productos'),
('productos_editar', 'Editar productos'),
('productos_eliminar', 'Eliminar productos'),

('clientes_ver', 'Ver clientes'),
('clientes_crear', 'Crear clientes'),
('clientes_editar', 'Editar clientes'),
('clientes_eliminar', 'Eliminar clientes'),

('proveedores_ver', 'Ver proveedores'),
('proveedores_crear', 'Crear proveedores'),
('proveedores_editar', 'Editar proveedores'),
('proveedores_eliminar', 'Eliminar proveedores'),

('ventas_ver', 'Ver ventas'),
('ventas_crear', 'Crear ventas'),
('ventas_editar', 'Editar ventas'),
('ventas_anular', 'Anular ventas'),

('caja_ver', 'Ver caja'),
('caja_abrir', 'Abrir caja'),
('caja_cerrar', 'Cerrar caja'),
('caja_movimientos', 'Registrar movimientos de caja'),

('reportes_ver', 'Ver reportes'),
('reportes_exportar', 'Exportar reportes'),

('empresa_ver', 'Ver datos de la empresa'),
('empresa_editar', 'Editar datos de la empresa');


-- =====================================================
-- PERMISOS PARA ADMINISTRADOR
-- =====================================================

INSERT INTO roles_permisos (id_rol, id_permiso)
SELECT
    1,
    id_permiso
FROM permisos;

-- Permisos para Cajero
INSERT INTO roles_permisos (id_rol, id_permiso)
SELECT r.id_rol, p.id_permiso
FROM roles r
INNER JOIN permisos p
    ON p.nombre IN (
        'clientes_ver', 'clientes_crear',
        'ventas_ver', 'ventas_crear',
        'caja_ver', 'caja_abrir', 'caja_cerrar', 'caja_movimientos'
    )
WHERE r.nombre = 'Cajero';

-- Permisos para Supervisor
INSERT INTO roles_permisos (id_rol, id_permiso)
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


-- =====================================================
-- CATEGORIAS INICIALES
-- =====================================================

INSERT INTO categorias
(categoria, descripcion)
VALUES
('General', 'Categoría general de productos');


-- =====================================================
-- USUARIO ADMINISTRADOR INICIAL
-- =====================================================

INSERT INTO usuarios
(
    nombre,
    usuario,
    correo,
    password,
    id_rol
)
VALUES
(
    'Administrador',
    'admin',
    'admin@localhost.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llCkLz2O9Z2y4G2JZr3W',
    1
);
