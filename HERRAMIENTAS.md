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

## Paso 6: API
docker compose exec backend php artisan make:request PersonajeRequest              -> crea la validación del formulario
docker compose exec backend php artisan make:request ImportarPersonajeRequest      -> crea la validación del ID de SWAPI
docker compose exec backend php artisan make:class Repositories/PersonajeRepository -> crea el repositorio
docker compose exec backend php artisan make:class Services/PersonajeService       -> crea el servicio de personajes
docker compose exec backend php artisan make:class Services/SwapiService           -> crea el servicio que consulta SWAPI
docker compose exec backend php artisan make:controller PersonajeController        -> crea el controlador
docker compose exec backend php artisan make:command ImportarPersonajes            -> crea el comando para importar varios personajes
docker compose exec backend php artisan make:test PersonajeTest                    -> crea las pruebas del CRUD
docker compose exec backend php artisan make:test ImportarPersonajeTest            -> crea las pruebas de la importación
app/Models/Personaje.php                                  -> géneros y máximos de altura y masa
app/Http/Requests/PersonajeRequest.php                    -> reglas de validación y mensajes en español
app/Http/Requests/ImportarPersonajeRequest.php            -> el ID de SWAPI debe estar entre 1 y 83
app/Repositories/PersonajeRepository.php                  -> consultas a la base de datos (solo personajes con estado = 1)
app/Services/PersonajeService.php                         -> listar, buscar, crear, actualizar, eliminar (estado = 0) e importar
app/Services/SwapiService.php                             -> GET a SWAPI, traduce los campos y maneja errores (timeout, 404)
app/Http/Controllers/PersonajeController.php              -> recibe la petición, llama al servicio y responde
app/Console/Commands/ImportarPersonajes.php               -> importa varios IDs desde la terminal
routes/web.php                                            -> las 7 rutas (CRUD e importar)
app/Http/Middleware/HandleInertiaRequests.php             -> comparte los mensajes de éxito y error con Vue
bootstrap/app.php                                         -> quita el token CSRF de las rutas de personajes
config/services.php, .env y .env.example                  -> URL de SWAPI (SWAPI_URL)
tests/Feature/ExampleTest.php y tests/Unit (borrados)     -> ejemplos de Laravel que no se usan
docker compose exec backend php artisan test                      -> ejecuta las pruebas
docker compose exec backend php artisan personajes:importar 1 2 3 -> importa los personajes 1, 2 y 3 desde SWAPI

## Paso 7: UI
resources/js/Layouts/AppLayout.vue            -> cabecera y mensajes de éxito o error
resources/js/Components/Modal.vue             -> ventana modal base reutilizable
resources/js/Components/PersonajeForm.vue     -> formulario reutilizable (crear y editar)
resources/js/Components/ImportModal.vue       -> importar por ID o desde la lista de SWAPI
resources/js/Components/Pagination.vue        -> paginación reutilizable
resources/js/Components/Spinner.vue           -> indicador de carga reutilizable
resources/js/Pages/Personajes/Index.vue       -> listado con código, fechas, búsqueda, filtro y modal de eliminar
resources/js/Pages/Personajes/Create.vue      -> página crear
resources/js/Pages/Personajes/Edit.vue        -> página editar
resources/css/app.css                         -> Tailwind solo revisa resources/ (más rápido en Docker)
docker-compose.yml                            -> el contenedor frontend compila con npm run build y termina
app/Repositories/PersonajeRepository.php      -> búsqueda por código o nombre y filtro por género
app/Services/SwapiService.php                 -> lista de personajes de SWAPI por página
app/Http/Middleware/HandleInertiaRequests.php -> sin versión de assets (Postman funciona solo con X-Inertia: true)
routes/web.php y PersonajeController.php      -> rutas y métodos en español (listarPersonajes, crearPersonaje...)
docker compose up -d frontend                 -> vuelve a compilar Vue después de cambiar un archivo .vue

## Referencias
https://hub.docker.com/_/php       -> imagen de PHP (Dockerfile del backend)
https://hub.docker.com/_/mysql     -> imagen de MySQL (contenedor db)
https://hub.docker.com/_/node      -> imagen de Node (contenedor frontend)
https://hub.docker.com/_/composer  -> imagen de Composer (se copia en el Dockerfile)
https://docs.docker.com/compose/   -> documentación de Docker Compose
https://inertiajs.com              -> documentación de Inertia
https://vuejs.org                  -> documentación de Vue
https://tailwindcss.com            -> documentación de Tailwind CSS
https://swapi.py4e.com/documentation -> documentación de SWAPI
https://laravel.com/docs/http-client -> cliente HTTP de Laravel (Http::get)
