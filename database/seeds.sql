-- =============================================================================
-- DATOS SEMILLA REFACTORIZADOS (v3): Sistema de Gestión Académica
-- Basado en el esquema Supertipo/Subtipo con datos personales en `usuarios`
-- Hash bcrypt para clave '12345678': $2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32
-- =============================================================================

USE `gestion_academica`;

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `inscripciones`;
TRUNCATE TABLE `estados_cursada`;
TRUNCATE TABLE `curso_profesor`;
TRUNCATE TABLE `cursos`;
TRUNCATE TABLE `alumnos`;
TRUNCATE TABLE `profesores`;
TRUNCATE TABLE `usuarios`;
TRUNCATE TABLE `roles`;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. Insertar Roles
INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Acceso total y gestión global del sistema'),
(2, 'Profesor', 'Gestión de cursos, asistencias y calificaciones'),
(3, 'Alumno', 'Consulta de inscripciones, materias y notas');

-- 2. Insertar Usuarios (Supertipo: Credenciales + Datos Personales)
INSERT INTO `usuarios` (`id`, `dni`, `apellido`, `nombre`, `email`, `telefono`, `password_hash`, `rol_id`) VALUES
(1, '11111111', 'Admin', 'Sistema', 'admin@profesorado.edu.ar', '11-1000-0000', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 1),
(2, '22222222', 'Gómez', 'Carlos', 'carlos.gomez@profesorado.edu.ar', '11-2000-0000', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 2),
(3, '33333333', 'López', 'María', 'maria.lopez@profesorado.edu.ar', '11-3000-0000', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 2),
(4, '44444444', 'Pérez', 'Juan', 'juan.perez@alumnos.edu.ar', '11-4000-0000', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 3),
(5, '55555555', 'Fernández', 'Ana', 'ana.fernandez@alumnos.edu.ar', '11-5000-0000', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 3),
(6, '66666666', 'Martínez', 'Lucas', 'lucas.martinez@alumnos.edu.ar', '11-6000-0000', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 3);

-- 3. Insertar Profesores (Subtipo)
INSERT INTO `profesores` (`id`, `usuario_id`) VALUES
(1, 2),
(2, 3);

-- 4. Insertar Alumnos (Subtipo)
INSERT INTO `alumnos` (`id`, `usuario_id`, `legajo`, `estado`) VALUES
(1, 4, 'LEG-2026-001', 'Activo'),
(2, 5, 'LEG-2026-002', 'Activo'),
(3, 6, 'LEG-2026-003', 'Activo');

-- 5. Insertar Cursos
INSERT INTO `cursos` (`id`, `nombre`, `materia`, `periodo_lectivo`) VALUES
(1, 'Programación Web II - Comisión A', 'Programación Web II', '2026 - 2do Cuatrimestre'),
(2, 'Bases de Datos - Comisión B', 'Bases de Datos', '2026 - 2do Cuatrimestre');

-- 6. Asignar Profesores a Cursos
INSERT INTO `curso_profesor` (`curso_id`, `profesor_id`) VALUES
(1, 1),
(2, 2);

-- 7. Insertar Estados de Cursada
INSERT INTO `estados_cursada` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Cursando', 'Alumno regular cursando activamente la asignatura'),
(2, 'Aprobado', 'Materia promocionada o aprobada en examen final'),
(3, 'Desaprobado', 'No alcanzó la calificación mínima requerida'),
(4, 'Libre', 'Perdió la regularidad por inasistencias o aplazamiento');

-- 8. Insertar Inscripciones
INSERT INTO `inscripciones` (`id`, `curso_id`, `alumno_id`, `estado_cursada_id`, `nota_final`) VALUES
(1, 1, 1, 1, NULL),
(2, 1, 2, 1, NULL),
(3, 2, 1, 2, 8.50),
(4, 2, 3, 1, NULL);
