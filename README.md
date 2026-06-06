# 🚗 AutoScan — Sistema de Gestión de Talleres Mecánicos

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red?style=flat-square&logo=laravel" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/React-18.3-61DAFB?style=flat-square&logo=react" alt="React 18.3" />
  <img src="https://img.shields.io/badge/TypeScript-5.7-3178C6?style=flat-square&logo=typescript&logoColor=white" alt="TypeScript 5.7" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS 3.4" />
  <img src="https://img.shields.io/badge/PostgreSQL-16-4169E1?style=flat-square&logo=postgresql&logoColor=white" alt="PostgreSQL 16" />
  <img src="https://img.shields.io/badge/Vite-6.0-646CFF?style=flat-square&logo=vite&logoColor=white" alt="Vite 6.0" />
  <img src="https://img.shields.io/badge/Inertia.js-2.0-purple?style=flat-square" alt="Inertia.js 2.0" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="MIT License" />
</p>

---

## 📋 Descripción

**AutoScan** es un sistema integral de gestión para talleres mecánicos que permite administrar de forma eficiente todas las operaciones del negocio. Desde la recepción de vehículos hasta la entrega final, AutoScan proporciona las herramientas necesarias para optimizar cada etapa del proceso de reparación.

### Características Principales

- 🏢 **Gestión Multi-Taller** — Administra uno o varios talleres desde una sola plataforma.
- 🚗 **Control de Vehículos** — Registro completo de vehículos, historial de servicios y datos del propietario.
- 📋 **Órdenes de Trabajo** — Creación, seguimiento y cierre de órdenes de trabajo con estados detallados.
- 🔧 **Gestión de Servicios** — Catálogo de servicios con precios, categorías y tiempos estimados.
- 👷 **Asignación de Mecánicos** — Asignación de tareas a mecánicos con control de especialidades.
- 📦 **Inventario de Repuestos** — Control de stock de repuestos con alertas de bajo inventario.
- 💰 **Facturación** — Generación de facturas y cotizaciones en PDF.
- 📊 **Dashboard Analítico** — Estadísticas y métricas del taller en tiempo real.
- 👤 **Gestión de Clientes** — Base de datos de clientes con historial de servicios.
- 🔔 **Notificaciones en Tiempo Real** — Alertas y notificaciones vía broadcasting.
- 🔐 **Roles y Permisos** — Control granular de acceso con Spatie Laravel Permission.
- 📱 **Diseño Responsivo** — Interfaz adaptada a dispositivos móviles y escritorio.

---

## 🛠️ Requisitos

Asegúrate de tener instalados los siguientes componentes antes de comenzar:

| Requisito | Versión Mínima |
|---|---|
| **PHP** | ^8.2 |
| **Composer** | ^2.7 |
| **Node.js** | ^18.0 |
| **npm** | ^9.0 |
| **PostgreSQL** | ^14.0 |
| **Extensión PHP pgsql** | Habilitada |
| **Extensión PHP BCMath** | Habilitada |
| **Extensión PHP Ctype** | Habilitada |
| **Extensión PHP Fileinfo** | Habilitada |
| **Extensión PHP JSON** | Habilitada |
| **Extensión PHP Mbstring** | Habilitada |
| **Extensión PHP OpenSSL** | Habilitada |
| **Extensión PHP PDO** | Habilitada |
| **Extensión PHP Tokenizer** | Habilitada |
| **Extensión PHP XML** | Habilitada |

---

## 📦 Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/autoscan.git
cd autoscan
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Configurar las variables de entorno

```bash
cp .env.example .env
```

Edita el archivo `.env` con tus credenciales de base de datos y configuración:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=autoscan
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
```

### 5. Generar la clave de la aplicación

```bash
php artisan key:generate
```

### 6. Ejecutar las migraciones y seeders

```bash
php artisan migrate --seed
```

### 7. Compilar los assets frontend

```bash
npm run dev
```

### 8. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`.

---

## 🏗️ Módulos del Sistema

### 1. 📊 Dashboard
Panel principal con estadísticas en tiempo real: órdenes activas, ingresos del mes, vehículos en proceso, métricas de productividad.

### 2. 🏢 Talleres
Gestión de sedes/talleres: configuración, horarios, datos de contacto, especialidades.

### 3. 👤 Usuarios y Roles
Administración de usuarios del sistema con roles definidos (Administrador, Recepcionista, Mecánico, Contador) y permisos granulares.

### 4. 👥 Clientes
CRUD completo de clientes: datos personales, vehículos asociados, historial de servicios, contacto.

### 5. 🚗 Vehículos
Registro de vehículos con datos técnicos: marca, modelo, año, placa, número de motor, kilometraje, historial.

### 6. 📋 Órdenes de Trabajo
Flujo completo de órdenes: creación, diagnóstico, asignación de mecánicos, seguimiento de progreso, cierre y facturación.

### 7. 🔧 Servicios
Catálogo de servicios mecánicos: descripción, precio base, categoría, tiempo estimado de ejecución.

### 8. 📦 Inventario / Repuestos
Control de inventario: entrada y salida de repuestos, stock mínimo, alertas de reposición, proveedores.

### 9. 💰 Facturación
Generación de facturas y cotizaciones: cálculo automático, exportación a PDF, historial de pagos.

### 10. 📈 Reportes
Reportes generables: ventas por período, servicios más solicitados, productividad por mecánico, inventario bajo.

---

## 🚀 Despliegue en Render.com

AutoScan está configurado para despliegue directo en [Render.com](https://render.com) mediante el archivo `render.yaml`.

### Pasos para despliegue:

1. **Crear cuenta** en [Render.com](https://render.com) (soporta plan gratuito).

2. **Conectar repositorio** de GitHub con el proyecto AutoScan.

3. **Render detectará automáticamente** el archivo `render.yaml` y configurará:
   - **Servicio Web** — Aplicación Laravel con servidor PHP integrado.
   - **Worker** — Procesador de colas en segundo plano.
   - **Base de datos PostgreSQL** — Base de datos dedicada.

4. **Configurar variables sensibles** en el dashboard de Render:
   - `APP_KEY` — Generada con `php artisan key:generate --show`
   - `MAIL_USERNAME` / `MAIL_PASSWORD` — Credenciales SMTP
   - `APP_URL` — URL de producción de tu aplicación

5. **Ejecutar migraciones** después del primer despliegue:
   ```bash
   # Desde el shell de Render:
   php artisan migrate --force
   php artisan db:seed --force
   ```

6. **Optimizar para producción** (ejecutar desde el shell de Render):
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```

### Notas sobre el plan gratuito de Render:
- El servidor usa `php -S 0.0.0.0:$PORT -t public` (servidor PHP integrado).
- Para planes pagos, se recomienda habilitar **Laravel Octane** para mejor rendimiento.
- La base de datos PostgreSQL del plan gratuito se duerme tras 90 días de inactividad.

---

## 🔧 Estructura del Proyecto

```
autoscan/
├── app/
│   ├── Console/
│   ├── Enums/
│   ├── Events/
│   ├── Exceptions/
│   ├── Exports/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Imports/
│   ├── Jobs/
│   ├── Listeners/
│   ├── Models/
│   ├── Notifications/
│   ├── Policies/
│   ├── Providers/
│   └── Services/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   │   └── app.css
│   └── js/
│       ├── Components/
│       ├── Hooks/
│       ├── Layouts/
│       ├── Pages/
│       ├── Services/
│       ├── Types/
│       ├── Utils/
│       └── app.tsx
├── routes/
│   ├── api.php
│   ├── channels.php
│   ├── console.php
│   └── web.php
├── storage/
├── tests/
├── broadcasting.php
├── composer.json
├── package.json
├── postcss.config.js
├── render.yaml
├── tailwind.config.js
├── tsconfig.json
├── tsconfig.node.json
├── vite.config.js
└── webpack.mix.js
```

---

## 🛠️ Tecnologías Utilizadas

| Tecnología | Propósito |
|---|---|
| **Laravel 12** | Framework PHP backend |
| **React 18.3** | Biblioteca UI frontend |
| **TypeScript 5.7** | Tipado estático en el frontend |
| **Inertia.js 2.0** | Puente entre Laravel y React (SPA sin API separada) |
| **Tailwind CSS 3.4** | Framework de utilidades CSS |
| **Vite 6.0** | Build tool y dev server |
| **PostgreSQL** | Base de datos relacional |
| **Laravel Sanctum 4.0** | Autenticación SPA / API |
| **Spatie Laravel Permission 6.0** | Roles y permisos |
| **Laravel DomPDF 2.0** | Generación de PDF |
| **Laravel Socialite 5.0** | Autenticación social (Google, Facebook) |
| **Heroicons 2.0** | Iconografía del sistema |

---

## 📄 Licencia

Este proyecto está licenciado bajo la **Licencia MIT**. Puedes usarlo, modificarlo y distribuirlo libremente.

---

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Para contribuir:

1. Haz un **fork** del repositorio.
2. Crea una **rama** con tu feature: `git checkout -b feature/nombre-feature`
3. Haz **commit** de tus cambios: `git commit -m 'Agregar nueva feature'`
4. Haz **push** a la rama: `git push origin feature/nombre-feature`
5. Abre un **Pull Request**.

---

<p align="center">
  Construido con ❤️ para la comunidad de talleres mecánicos
</p>
