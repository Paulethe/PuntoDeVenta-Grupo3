puntoVenta
-- =====================================================
-- ROLES
-- =====================================================
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    estado TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_roles_estado (estado)
);

-- =====================================================
-- CATEGORIAS
-- =====================================================
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    id_categoria_padre INT NULL,
    orden INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_categorias_padre
        FOREIGN KEY (id_categoria_padre)
        REFERENCES categorias(id_categoria)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    
    INDEX idx_categorias_estado (estado),
    INDEX idx_categorias_padre (id_categoria_padre),
    INDEX idx_categorias_orden (orden)
);

-- =====================================================
-- DATOS INICIALES: ROLES
-- =====================================================
INSERT INTO roles (nombre, descripcion) VALUES
('Administrador', 'Acceso total al sistema'),
('Cajero', 'Realiza ventas y operaciones de caja'),
('Supervisor', 'Supervisa operaciones y reportes');

-- =====================================================
-- CATEGORIAS INICIALES
-- =====================================================
INSERT INTO categorias (nombre, descripcion, orden) VALUES
('General', 'Categoría general de productos', 1),
('Bebidas', 'Bebidas y refrescos', 2),
('Alimentos', 'Productos alimenticios', 3),
('Limpieza', 'Productos de limpieza', 4);