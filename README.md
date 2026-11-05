# Sistema de Gestión Académica - Profesorado de Informática

Sistema de gestión académica web con enfoque **Mobile-First**, desarrollado en **PHP, MySQL y Bootstrap 5**, containerizado mediante **Docker y Docker Compose**. El proyecto está estructurado con una arquitectura modular y un modelo de datos altamente normalizado en **Tercera Forma Normal (3FN)** utilizando el patrón relacional **Supertipo/Subtipo**.

---

## 📚 Documentación del Proyecto

La documentación detallada y la planificación ágil se encuentran organizadas dentro de la carpeta [`doc/`](./doc/):

0. **[doc/00-tablero-proyecto.md](./doc/00-tablero-proyecto.md)**: Plan de trabajo y seguimiento de historias de usuario en formato Kanban / Trello (Criterios de evaluación 10/10, tareas completadas, en proceso y backlog).
1. **[doc/01-modelo-datos.md](./doc/01-modelo-datos.md)**: Especificación técnica del modelo relacional, diagrama Entidad-Relación (Mermaid), justificación de normalización 3FN y patrón Supertipo/Subtipo (`usuarios` ↔ `alumnos` / `profesores`).
2. **[doc/02-seguridad.md](./doc/02-seguridad.md)**: Mecanismos de seguridad, autenticación por DNI, hashing de contraseñas con `bcrypt`, control de sesiones y matriz de permisos por Rol (RBAC).
3. **[doc/03-alumnos.md](./doc/03-alumnos.md)**: Especificación funcional del módulo de alumnos, transacciones atómicas PDO, matriz de permisos de edición/baja y autogestión de perfil.

---

## 🚀 Arquitectura y Tecnologías

* **Backend**: PHP 8.4 (PDO con consultas preparadas y cotejamiento `utf8mb4`).
* **Base de Datos**: MySQL 8.4 (Motor InnoDB, codificación `utf8mb4_unicode_ci`).
* **Frontend**: HTML5, CSS3, JavaScript ES6, **Bootstrap 5.3** y **Bootstrap Icons** (Estilos e íconos alojados localmente para soporte offline).
* **Infraestructura**: Docker & Docker Compose (`web` Apache/PHP + `db` MySQL 8.4).

---

## 📂 Estructura del Proyecto

```text
.
├── app/
│   ├── config/
│   │   └── database.php          # Conexión PDO con utf8mb4 y manejo de excepciones
│   ├── includes/                 # Plantillas de UI Mobile-First
│   │   ├── header.php            # Head HTML y llamadas a CSS local
│   │   ├── navbar.php            # Menú navegable responsive según el Rol activo
│   │   └── footer.php            # Cierre HTML y scripts JS locales
│   ├── modules/                  # Lógica de negocio y persistencia
│   │   ├── auth.php              # Autenticación, sesiones y perfil de usuario
│   │   └── alumnos.php           # CRUD atómico y consultas de Alumnos
│   └── public/                   # Punto de entrada público (Navegador)
│       ├── css/                  # Bootstrap 5 y Bootstrap Icons locales
│       ├── js/                   # Bootstrap JS Bundle local
│       ├── login.php             # Formulario de inicio de sesión Mobile-First
│       ├── logout.php            # Cierre seguro de sesión
│       ├── index.php             # Panel de control / Dashboard con KPI
│       ├── alumnos.php           # Gestión visual de alumnos (Cards / Tabla)
│       └── perfil.php            # Autogestión de perfil y cambio de clave
├── database/
│   ├── schema.sql                # DDL del esquema relacional (8 tablas 3FN)
│   └── seeds.sql                 # Datos de prueba iniciales con hashes bcrypt
├── doc/                          # Documentación técnica y gestión de proyecto en Markdown
│   ├── 00-tablero-proyecto.md
│   ├── 01-modelo-datos.md
│   ├── 02-seguridad.md
│   └── 03-alumnos.md
├── compose.yaml                  # Orquestador Docker Compose
├── Dockerfile                    # Imagen personalizada de PHP 8.4 + pdo_mysql
└── README.md                     # Portal de bienvenida e instrucciones
```

---

## 🛠️ Instalación y Despliegue con Docker

### Requisitos Previos
* Docker Engine y Docker Compose instalados en el sistema host.

### Pasos para Ejecutar

1. **Clonar el repositorio y ubicarse en la raíz:**
   ```bash
   git clone https://github.com/gcolazo/gestion-academica
   cd gestion-academica
   ```

2. **Levantar los contenedores Docker:**
   ```bash
   docker compose up -d
   ```

3. **Inicializar la Base de Datos (Primera vez):**
   Si los scripts SQL no se ejecutaron automáticamente por existencia previa del volumen Docker:
   ```bash
   docker exec -i docker_test-db-1 mysql -u root -pcambiar_root_123 gestion_academica < database/schema.sql
   docker exec -i docker_test-db-1 mysql -u root -pcambiar_root_123 gestion_academica < database/seeds.sql
   ```

4. **Acceder a la aplicación desde el navegador:**
   [http://localhost:8080/login.php](http://localhost:8080/login.php)

---

## 🔑 Credenciales de Prueba

Para testear la aplicación y los distintos niveles de permisos de usuario:

| Rol | DNI | Contraseña | Permisos Destacados |
| :--- | :---: | :---: | :--- |
| **Administrador** | `11111111` | `12345678` | Acceso total (Alta, Edición y **Baja** de registros). |
| **Profesor** | `22222222` | `12345678` | Alta y Edición de alumnos. Sin permiso de eliminación. |
| **Alumno** | `44444444` | `12345678` | Redirección automática a su perfil (`perfil.php`) para autogestión. |
