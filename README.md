# NAB - Peluquería + Tienda (Laravel 11)

Proyecto MVP de reservas de turnos de peluquería y venta de postres/encargos. Stack: Laravel 11, Blade, Tailwind, Alpine, MySQL (o SQLite), Mercado Pago Checkout Pro.

Estructura del código en `app/`.

Instalación rápida (local)
- Requisitos: PHP 8.2+, Composer, Node 18+.
- Crear .env: copiar `app/.env.example` a `app/.env` y ajustar DB/MAIL/MP.
- DB: por defecto usa SQLite. Para MySQL setear `DB_CONNECTION=mysql`, host, usuario y base.
- Instalar dependencias: `cd app && composer install`
- Generar key: `php artisan key:generate`
- Migraciones y seeders: `php artisan migrate --seed`
- Compilar assets (ya compila con Breeze, pero si modificás): `npm run build`
- Levantar: `php artisan serve` y abrir http://localhost:8000

Usuarios demo
- Admin: admin@example.com / password
- Cliente: cliente@example.com / password

Variables .env principales (`app/.env`)
- Base de datos: `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
- Mail SMTP: `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`.
- Mercado Pago: `MERCADOPAGO_ACCESS_TOKEN` (sandbox en desarrollo).

Pagos (Mercado Pago)
- El servicio crea preferencias con título dinámico y `back_urls` y usa `/webhook/mercadopago` para actualizar estados.
- Ajustá el Webhook en tu app de MP apuntando a `https://tu-dominio/webhook/mercadopago`.

Settings (negocio)
- En Admin > Settings podés cambiar % de seña, ventana de cancelación (h), buffer entre turnos, teléfono de WhatsApp y textos legales.

Comandos útiles
- `php artisan migrate:fresh --seed` rehace la BD.
- `php artisan tinker` pruebas rápidas.

Docker (dev opcional)
- Ver `app/Dockerfile` y `app/docker-compose.yml` para correr con PHP-FPM + Nginx + MySQL. Ajustá volúmenes y `.env`.

Deploy barato
- DB: PlanetScale (MySQL serverless free) o Railway. Configurar credenciales en `.env`.
- Backend: Render/Railway con deploy desde Git. Setear variables `.env`. DocumentRoot a `public` si subís a cPanel.
- Storage: `php artisan storage:link` para servir `public/storage`.
- Mail: SMTP (Gmail con app password o tu proveedor).
- SSL: Render/Railway autossl; en cPanel usar Let’s Encrypt.

Notas
- Slots generados por `App\Services\SlotGenerator` con tests básicos.
- Pagos por `App\Services\PaymentsService`. Webhook sin CSRF.
- Recordatorios automáticos por scheduler (cada hora) 24h antes del turno.
