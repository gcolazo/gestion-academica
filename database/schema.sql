-- =============================================================================
-- ESQUEMA DE BASE DE DATOS: Sistema de Gestión Académica
-- Asignatura: Programación Web II / Profesorado de Informática
-- Normalización: Tercera Forma Normal (3FN)
-- Motor: InnoDB | Cotejamiento: utf8mb4_unicode_ci
-- =============================================================================

CREATE DATABASE IF NOT EXISTS `gestion_academica` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `gestion_academica`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `inscripciones`;
DROP TABLE IF EXISTS `estados_cursada`;
DROP TABLE IF EXISTS `curso_profesor`;
DROP TABLE IF EXISTS `cursos`;
DROP TABLE IF EXISTS `alumnos`;
DROP TABLE IF EXISTS `profesores`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `roles`;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. Tabla: roles
CREATE TABLE `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(50) NOT NULL UNIQUE,
  `descripcion` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabla: usuarios (Login por DNI)
CREATE TABLE `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `dni` VARCHAR(20) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `rol_id` INT NOT NULL,
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_usuarios_roles` 
    FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) 
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabla: profesores
CREATE TABLE `profesores` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL UNIQUE,
  `apellido` VARCHAR(100) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `telefono` VARCHAR(50) NULL,
  CONSTRAINT `fk_profesores_usuarios` 
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabla: alumnos
CREATE TABLE `alumnos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL UNIQUE,
  `legajo` VARCHAR(50) NOT NULL UNIQUE,
  `apellido` VARCHAR(100) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `telefono` VARCHAR(50) NULL,
  `estado` ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo',
  CONSTRAINT `fk_alumnos_usuarios` 
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabla: cursos
CREATE TABLE `cursos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `materia` VARCHAR(100) NOT NULL,
  `periodo_lectivo` VARCHAR(50) NOT NULL,
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabla: curso_profesor (Relación M:N Cursos-Profesores)
CREATE TABLE `curso_profesor` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `curso_id` INT NOT NULL,
  `profesor_id` INT NOT NULL,
  UNIQUE KEY `uk_curso_profesor` (`curso_id`, `profesor_id`),
  CONSTRAINT `fk_cp_curso` 
    FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cp_profesor` 
    FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tabla: estados_cursada (Normalización 3FN)
CREATE TABLE `estados_cursada` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(50) NOT NULL UNIQUE,
  `descripcion` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Tabla: inscripciones (Relación M:N Cursos-Alumnos)
CREATE TABLE `inscripciones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `curso_id` INT NOT NULL,
  `alumno_id` INT NOT NULL,
  `estado_cursada_id` INT NOT NULL,
  `fecha_inscripcion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `nota_final` DECIMAL(4,2) NULL,
  UNIQUE KEY `uk_curso_alumno` (`curso_id`, `alumno_id`),
  CONSTRAINT `fk_inscripciones_cursos` 
    FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_inscripciones_alumnos` 
    FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_inscripciones_estados` 
    FOREIGN KEY (`estado_cursada_id`) REFERENCES `estados_cursada` (`id`) 
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;