# 💸 Agenda de Pagos

Sistema web de prueba para la práctica de **despliegue con GitHub**: una agenda de
recordatorios de pagos de servicios (luz, agua, gas, internet, streaming como
Netflix, Disney+, Spotify, etc.).

## ✨ Funcionalidades

- 📊 **Panel principal**: resumen de servicios activos, pagos del mes, pendientes,
  vencidos y total pagado, con cálculo automático de la próxima fecha de vencimiento.
- 🧾 **Servicios**: registro con nombre, categoría, costo mensual y día de vencimiento.
- 👥 **Personas**: registro de las personas que pagan.
- 💳 **Pagos**: registro con fecha, monto y estado (pagado / pendiente / vencido).
- 🤝 **Pagos compartidos**: un pago puede repartirse entre varias personas con el
  monto que aporta cada una (ideal para un plan familiar de Spotify).
- ✅ Validaciones en español y mensajes flash de confirmación/error.

## 🛠️ Stack

| Capa      | Tecnología          |
|-----------|---------------------|
| Backend   | Laravel 12 (PHP 8.2)|
| Frontend  | Vite + Blade + CSS  |
| Base de datos | PostgreSQL     |

## 📁 Estructura de datos

- `people` — personas que realizan pagos.
- `services` — servicios con costo y día de vencimiento.
- `payments` — pagos registrados (pertenecen a un servicio).
- `payment_person` — tabla pivote: qué persona aportó cuánto en cada pago.

## 🚀 Instalación local

### Requisitos
- PHP >= 8.2 (con extensiones `pdo_pgsql` y `pgsql`)
- Composer
- Node.js >= 20 y npm
- PostgreSQL con una base de datos creada

### Pasos

```bash
# 1. Clonar el repositorio
git clone <url-del-repo>
cd Practica_despliegue

# 2. Instalar dependencias de PHP
composer install

# 3. Copiar el .env y generar la clave
cp .env.example .env
php artisan key:generate

# 4. Configurar la base de datos en el .env
#    DB_CONNECTION=pgsql
#    DB_HOST=127.0.0.1
#    DB_PORT=5432
#    DB_DATABASE=despliegue_prueba
#    DB_USERNAME=postgres
#    DB_PASSWORD=tu-contraseña

# 5. Crear tablas y cargar datos de ejemplo (opcional)
php artisan migrate
php artisan db:seed        # datos de ejemplo: personas, servicios y pagos

# 6. Instalar dependencias del frontend y compilar
npm install
npm run build

# 7. Levantar el servidor
php artisan serve
```

Abre `http://localhost:8000` 🎉

### Desarrollo con Vite (hot reload)

```bash
php artisan serve          # terminal 1
npm run dev                # terminal 2
```

## 🧪 Tests

```bash
php artisan test
```

Incluye pruebas de: dashboard, registro de servicios, pagos compartidos entre
varias personas y protección al eliminar personas con pagos asociados.

## 🌍 Despliegue a internet (práctica de GitHub)

### 1. Subir el código a GitHub

```bash
git init
git add .
git commit -m "Primer commit: agenda de pagos"
git branch -M main
git remote add origin https://github.com/<tu-usuario>/<nombre-repo>.git
git push -u origin main
```

> ⚠️ El archivo `.env` **nunca** se sube (está en `.gitignore`). Las credenciales
> se configuran como variables de entorno en el servidor.

### 2. Desplegar (Railway / Render / Heroku)

Cada plataforma detecta Laravel automáticamente. Lo que necesitas configurar:

| Variable de entorno  | Ejemplo                                   |
|----------------------|-------------------------------------------|
| `APP_ENV`            | `production`                              |
| `APP_KEY`            | (genera una: `php artisan key:generate`)  |
| `APP_DEBUG`          | `false`                                   |
| `APP_URL`            | `https://tu-app.up.railway.app`           |
| `DB_CONNECTION`      | `pgsql`                                   |
| `DB_HOST` / `DB_PORT`| los del proveedor de BD                    |
| `DB_DATABASE`        | `despliegue_prueba`                       |
| `DB_USERNAME`        | el usuario del proveedor                  |
| `DB_PASSWORD`        | la contraseña del proveedor               |

**Build command** (si la plataforma lo pide): `composer install && npm install && npm run build`

**Start command**: `php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT`

### Errores comunes al desplegar (¡esta es tu práctica! 😄)

1. **"No such file or directory: .../storage"** → dar permisos: `chmod -R 775 storage bootstrap/cache`
2. **Error 500 / página blanca** → revisa los logs (`storage/logs/laravel.log`) y pon `APP_DEBUG=true` temporalmente.
3. **Assets sin estilos** → olvidaste `npm run build` (o el build command del servidor).
4. **"Connection refused" a la BD** → las variables `DB_*` están mal o el host no es accesible.
5. **419 Page Expired en formularios** → el `SESSION_DRIVER` no está configurado (usa `database`) o `APP_URL` no coincide con el dominio real.

## 📄 Licencia

Proyecto educativo para la materia Seminario de Software.
