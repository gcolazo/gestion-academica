# Especificación Técnica y Funcional: Módulo de Seguridad (Login / Logout)

**Asignatura**: Programación Web II  
**Módulo**: Seguridad y Autenticación  
**Tecnologías**: PHP 8.x, MySQL 8.x (PDO), Bootstrap 5, Sessions  

---

## 1. Alcance y Requerimientos Funcionales

* **Identificador de Acceso**: DNI (Cadena única de caracteres numéricos sin puntos).
* **Almacenamiento de Contraseñas**: Encriptación hash unidireccional utilizando  (BCrypt / Argon2ID).
* **Manejo de Sesiones**: Control de estado de autenticación mediante  de PHP.
* **Cierre de Sesión (Logout)**: Destrucción completa de la sesión activa y limpieza de cookies de sesión.
* **Control de Permisos**: Redirección automática de usuarios no autenticados o con rol inadecuado.
* **Diseño UI**: Interfaz orientada a dispositivos móviles (**Mobile-First**) estructurada con Bootstrap 5.

---

## 2. Arquitectura de Componentes



---

## 3. Flujo de Autenticación (Paso a Paso)



---

## 4. Estructura de la Sesión ()

Al autenticarse correctamente, se guardan en sesión los siguientes datos esenciales:



---

## 5. Medidas de Seguridad Aplicadas

1. **Prevención de Inyección SQL**: Consultas preparadas exclusivamente con  y binding explícito de parámetros.
2. **Protección XSS**: Escapado de salida HTML mediante .
3. **Validación Hash**: Verificación segura contra ataques de tiempo mediante .
4. **Regeneración de ID de Sesión**: Invocación de  tras el login exitoso para prevenir ataques de *Session Fixation*.



# Usuarios
    Administrador: DNI 11111111 | Contraseña: 12345678
    Profesor (Carlos Gómez): DNI 22222222 | Contraseña: 12345678
    Alumno (Juan Pérez): DNI 44444444 | Contraseña: 12345678
