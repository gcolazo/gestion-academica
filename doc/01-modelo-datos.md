# Documentación del Modelo de Datos y Normalización

**Asignatura**: Programación Web II  
**Proyecto**: Sistema de Gestión Académica para Profesorado en Informática  

---

## 1. Introducción y Enfoque de Evaluación

El presente documento detalla la estructura, relaciones y justificativos de diseño de la base de datos para el **Sistema de Gestión Académica**. Atendiendo al criterio prioritario de evaluación (estricta estructuración, integridad referencial y demostración práctica), se ha diseñado una arquitectura relacional basada en MySQL / MariaDB bajo el motor **InnoDB** y codificación **utf8mb4_unicode_ci**.

---

## 2. Diagrama Entidad-Relación (DER)

```mermaid
erDiagram
    roles ||--o{ usuarios : "asigna permisos a"
    usuarios ||--o| profesores : "extiende datos personales de"
    usuarios ||--o| alumnos : "extiende datos personales de"
    profesores }|--m| cursos : "asociados mediante curso_profesor"
    cursos ||--o{ inscripciones : "posee matriculados"
    alumnos ||--o{ inscripciones : "cursa asignaturas"
    estados_cursada ||--o{ inscripciones : "clasifica condicion de"