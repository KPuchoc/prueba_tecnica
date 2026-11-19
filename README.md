# Sistema de Gestión de Personajes Star Wars

## Información del Candidato

- Nombre: Kenny William Puchoc Laura
- Email: kpuchocl@gmail.com
- Teléfono: +51 968844302

## Requisitos del Sistema

- PHP 8.3+
- Composer
- Node.js 22+
- MySQL 8+


## Instalación con Docker

```bash
git clone https://github.com/KPuchoc/prueba_tecnica.git
cd prueba_tecnica
cp .env.example .env
docker compose up -d --build
```

La primera vez tarda unos minutos. Cuando `docker compose logs backend` muestre "Server running", acceder a http://localhost:8000

Para apagar: `docker compose down`

## Instalación

1. Clonar repositorio

```bash
git clone https://github.com/KPuchoc/prueba_tecnica.git
cd prueba_tecnica
```

2. Instalar dependencias

```bash
composer install
npm install
```

3. Configurar base de datos

```bash
cp .env.example .env
```

Editar `.env` con las credenciales de MySQL y luego:

```bash
php artisan key:generate
```

4. Ejecutar migraciones

```bash
php artisan migrate
```

5. Compilar assets

```bash
npm run build
```

6. Iniciar servidor

```bash
php artisan serve
```

7. Acceder

http://localhost:8000

## Tecnologías

- Laravel 13
- Vue.js 3
- Inertia.js
- Tailwind CSS
- Vite
- MySQL
- Docker
