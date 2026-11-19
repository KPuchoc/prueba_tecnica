# Comandos usados

## Paso 0: Repositorio
git clone https://github.com/KPuchoc/prueba_tecnica.git  -> descarga el repositorio de GitHub

## Paso 1: Proyecto Laravel
composer global require laravel/installer  -> instala el comando laravel
laravel new prueba_tecnica                 -> crea el proyecto
.env y .env.example     -> se dejan solo las variables APP_* y DB_*

## Paso 2: Docker
mkdir docker\backend                       -> crea la carpeta del contenedor backend
New-Item docker\backend\Dockerfile         -> crea el Dockerfile del backend (se escribe a mano: PHP + extensiones + Composer)
New-Item docker-compose.yml                -> crea el archivo de los 3 contenedores (se escribe a mano: db, backend, frontend)
vite.config.js (editado a mano)            -> configura Vite para que funcione dentro de Docker
docker compose up -d --build               -> construye las imágenes y levanta los contenedores
docker compose ps                          -> muestra los contenedores y su estado
docker compose logs backend                -> muestra los mensajes del backend
docker compose down                        -> apaga los contenedores

## Paso 3: Inertia y Vue
docker compose exec backend composer require inertiajs/inertia-laravel          -> instala Inertia en Laravel
docker compose exec backend php artisan inertia:middleware                      -> crea el middleware de Inertia
docker compose exec frontend npm install vue @vitejs/plugin-vue @inertiajs/vue3  -> instala Vue e Inertia para Vue

## Paso 4: Conectar Laravel con Vue
New-Item resources\views\app.blade.php     -> crea la vista principal donde Inertia carga Vue (se escribe a mano)
resources/js/app.js                        -> arranca Vue con Inertia y busca las páginas en resources/js/Pages
vite.config.js                             -> agrega el plugin de Vue
bootstrap/app.php                          -> registra el middleware de Inertia

## Paso 5: Base de datos
docker compose exec backend php artisan make:model Personaje -m   -> crea el modelo Personaje y su migración
database/migrations/..._create_personajes_table.php               -> se agregan los campos de la tabla
app/Models/Personaje.php                                          -> se indican los campos que se pueden guardar
docker compose exec backend php artisan migrate                   -> crea la tabla en MySQL
docker compose exec backend php artisan migrate:rollback          -> deshace la última migración (para corregirla y volver a ejecutar migrate)

## Referencias
https://hub.docker.com/_/php       -> imagen de PHP (Dockerfile del backend)
https://hub.docker.com/_/mysql     -> imagen de MySQL (contenedor db)
https://hub.docker.com/_/node      -> imagen de Node (contenedor frontend)
https://hub.docker.com/_/composer  -> imagen de Composer (se copia en el Dockerfile)
https://docs.docker.com/compose/   -> documentación de Docker Compose
https://inertiajs.com              -> documentación de Inertia
https://vuejs.org                  -> documentación de Vue
https://tailwindcss.com            -> documentación de Tailwind CSS
