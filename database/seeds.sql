-- =============================================================================
-- DATOS SEMILLA (SEEDS): Sistema de Gestión Académica
-- =============================================================================

USE `gestion_academica`;

-- 1. Insertar Roles por Defecto
INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Acceso total y gestión completa del sistema'),
(2, 'Profesor', 'Gestión de sus cursos asignados, listado de alumnos y notas'),
(3, 'Alumno', 'Consulta de sus cursos e inscripciones activas');

-- 2. Insertar Catálogo de Estados de Cursada
INSERT INTO `estados_cursada` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Inscripto', 'Alumno matriculado al inicio del período lectivo'),
(2, 'Regular', 'Alumno que cumplió los requisitos mínimos de cursada'),
(3, 'Aprobado', 'Alumno con la asignatura acreditada'),
(4, 'Promocionado', 'Aprobado por promoción directa sin examen final'),
(5, 'Desaprobado', 'Alumno que no alcanzó la nota mínima de aprobación'),
(6, 'Libre', 'Alumno dado de baja o que perdió la regularidad');

-- 3. Insertar Usuarios de Prueba (Password por defecto para todos: '12345678')
-- Hash de Bcrypt equivalente para la contraseña '12345678'
INSERT INTO `usuarios` (`id`, `dni`, `password_hash`, `rol_id`) VALUES
(1, '11111111', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 1), -- Admin
(2, '22222222', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 2), -- Prof. Gómez
(3, '33333333', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 2), -- Prof. Martínez
(4, '44444444', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 3), -- Alumno Pérez
(5, '55555555', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 3), -- Alumno Rodríguez
(6, '66666666', '$2y$10$22CharactersSaltHere2u6KHH4hlZwjkWTpmb1HgmN/nGyJB2J32', 3); -- Alumna López

-- 4. Insertar Profesores
INSERT INTO `profesores` (`id`, `usuario_id`, `apellido`, `nombre`, `email`, `telefono`) VALUES
(1, 2, 'Gómez', 'Carlos Alberto', 'carlos.gomez@profesorado.edu.ar', '11-4567-8901'),
(2, 3, 'Martínez', 'Laura Sofía', 'laura.martinez@profesorado.edu.ar', '11-5678-9012');

-- 5. Insertar Alumnos
INSERT INTO `alumnos` (`id`, `usuario_id`, `legajo`, `apellido`, `nombre`, `email`, `telefono`, `estado`) VALUES
(1, 4, 'LEG-2024-001', 'Pérez', 'Juan Manuel', 'juan.perez@alumnos.edu.ar', '11-2345-6789', 'Activo'),
(2, 5, 'LEG-2024-002', 'Rodríguez', 'María Belén', 'maria.rodriguez@alumnos.edu.ar', '11-3456-7890', 'Activo'),
(3, 6, 'LEG-2024-003', 'López', 'Lucas Agustín', 'lucas.lopez@alumnos.edu.ar', '11-6789-0123', 'Activo');

-- 6. Insertar Cursos
INSERT INTO `cursos` (`id`, `nombre`, `materia`, `periodo_lectivo`) VALUES
(1, 'PW2-2026-1C', 'Programación Web II', '2026 - 1er Cuatrimestre'),
(2, 'BD1-2026-1C', 'Bases de Datos I', '2026 - 1er Cuatrimestre'),
(3, 'SO-2026-2C', 'Sistemas Operativos', '2026 - 2do Cuatrimestre');

-- 7. Asignar Profesores a Cursos (Demostración de relación M:N)
INSERT INTO `curso_profesor` (`curso_id`, `profesor_id`) VALUES
(1, 1), -- Gómez dicta PW2
(2, 1), -- Gómez también dicta BD1 (Un profesor en varios cursos)
(2, 2); -- Martínez co-dicta BD1

-- 8. Inscripciones de Alumnos en Cursos
INSERT INTO `inscripciones` (`curso_id`, `alumno_id`, `estado_cursada_id`, `nota_final`) VALUES
(1, 1, 1, NULL), -- Juan Pérez en PW2 (Inscripto)
(1, 2, 4, 9.50), -- María Rodríguez en PW2 (Promocionado)
(2, 1, 2, 7.00), -- Juan Pérez en BD1 (Regular)
(2, 3, 1, NULL); -- Lucas López en BD1 (Inscripto)
