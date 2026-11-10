# Sistema de Gestión Académica - Profesorado de Informática

El **Sistema de Gestión Académica** es una plataforma web integral diseñada para la administración y control de la información institucional en un entorno educativo. Su objetivo principal es centralizar la gestión de los estudiantes, la actualización de sus datos personales, el seguimiento de legajos y el control de accesos desde una interfaz intuitiva y completamente adaptada a dispositivos móviles (Mobile-First).

La plataforma opera bajo un esquema de **seguridad y control de acceso basado en roles (RBAC)** que segmenta la experiencia de usuario según su perfil dentro de la institución. Los **Administradores** disponen de control total sobre el ciclo de vida de los datos (alta, modificación, búsqueda avanzada y eliminación definitiva); los **Profesores** cuentan con facultades para dar de alta alumnos, consultar legajos y actualizar información de contacto; mientras que los **Alumnos** acceden a un entorno de autogestión de perfil donde pueden visualizar sus datos académicos y actualizar sus vías de contacto y contraseña.

Para garantizar una usabilidad ágil, la aplicación integra un **buscador unificado multicriterio** en la vista de alumnos. Esto permite a directivos y docentes localizar al instante cualquier registro ingresando indistintamente el DNI, legajo, nombre, apellido o correo electrónico. Además, la interfaz responsiva ajusta dinámicamente las tablas de datos transformándolas en tarjetas táctiles cuando se navega desde teléfonos celulares o tablets.

---

## 📚 Documentación del Proyecto

La documentación detallada y la planificación ágil se encuentran organizadas dentro de la carpeta [`doc/`](./doc/):

0. **[doc/00-tablero-proyecto.md](./doc/00-tablero-proyecto.md)**: Plan de trabajo y seguimiento de historias de usuario en formato Kanban / Trello (Criterios de evaluación 10/10, tareas completadas, en proceso y backlog).
1. **[doc/01-modelo-datos.md](./doc/01-modelo-datos.md)**: Especificación técnica del modelo relacional, diagrama Entidad-Relación (Mermaid), justificación de normalización 3FN y patrón Supertipo/Subtipo (`usuarios` ↔ `alumnos` / `profesores`).
2. **[doc/02-seguridad.md](./doc/02-seguridad.md)**: Mecanismos de seguridad, autenticación por DNI, hashing de contraseñas con `bcrypt`, control de sesiones y matriz de permisos por Rol (RBAC).
3. **[doc/03-alumnos.md](./doc/03-alumnos.md)**: Especificación funcional del módulo de alumnos, **buscador multicriterio (DNI, Legajo, Nombre, Email)**, transacciones atómicas PDO, binding seguro `:b1..:b5` y autogestión de perfil.

---

## 🚀 Arquitectura y Tecnologías

* **Backend**: PHP 8.4 (PDO con consultas preparadas nativas y binding múltiple `:b1..:b5`).
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
│   │   └── alumnos.php           # CRUD atómico y buscador multicriterio de Alumnos
│   └── public/                   # Punto de entrada público (Navegador)
│       ├── css/                  # Bootstrap 5 y Bootstrap Icons locales
│       ├── js/                   # Bootstrap JS Bundle local
│       ├── login.php             # Formulario de inicio de sesión Mobile-First
│       ├── logout.php            # Cierre seguro de sesión
│       ├── index.php             # Panel de control / Dashboard con KPI
│       ├── alumnos.php           # ABM y Buscador de alumnos (Cards / Tabla)
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
   git clone <URL_DEL_REPOSITORIO>
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
| **Administrador** | `11111111` | `12345678` | Acceso total (Alta, Búsqueda, Edición y **Baja** de registros). |
| **Profesor** | `22222222` | `12345678` | Alta, Búsqueda y Edición de alumnos. Sin permiso de eliminación. |
| **Alumno** | `44444444` | `12345678` | Redirección automática a su perfil (`perfil.php`) para autogestión. |
