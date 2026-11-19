# Sistema de Gestión de Personajes Star Wars

## Información del Candidato

- Nombre: Kenny William Puchoc Laura
- Email: kpuchocl@gmail.com
- Teléfono: +51 968844302

## Requisitos del Sistema

- PHP 8.3+ (Laravel 13 lo requiere)
- Composer
- Node.js 22+
- MySQL 8+

O solo Docker Desktop, si se usa la instalación con Docker.

## Instalación con Docker

```bash
git clone https://github.com/KPuchoc/prueba_tecnica.git
cd prueba_tecnica
cp .env.example .env
docker compose up -d --build
```

La primera vez tarda unos minutos. Cuando `docker compose logs backend` muestre "Server running", acceder a http://localhost:8000/listaPersonajes

| Contenedor | Qué hace | Puerto |
|---|---|---|
| `db` | MySQL 8.4 | 3307 |
| `backend` | Laravel (instala dependencias, genera la clave, migra y levanta el servidor) | 8000 |
| `frontend` | compila Vue y Tailwind con Vite (`npm run build`) y termina | — |

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

http://localhost:8000/listaPersonajes

## Comandos útiles

```bash
php artisan personajes:importar 1 2 3 4 5   # importa varios personajes desde SWAPI
php artisan test                            # ejecuta las pruebas
```

Con Docker, anteponer `docker compose exec backend` (por ejemplo `docker compose exec backend php artisan test`).

Todos los comandos usados para construir el proyecto están en [HERRAMIENTAS.md](HERRAMIENTAS.md).

## Rutas

| Método | URL | Qué hace |
|---|---|---|
| GET | `/listaPersonajes` | lista los personajes (10 por página, búsqueda y filtro) |
| GET | `/personaje/crear` | formulario de creación |
| POST | `/crearPersonaje` | guarda un personaje nuevo |
| GET | `/personaje/{id}/editar` | formulario de edición |
| PUT | `/actualizar-personaje/{id}` | actualiza un personaje |
| DELETE | `/eliminar-personaje/{id}` | elimina un personaje (pone `estado = 0`) |
| GET | `/listaPersonajesSwapi?pagina=1` | lista los personajes de SWAPI y marca los ya importados |
| POST | `/importarPersonaje` | importa un personaje desde SWAPI |

Para probar en Postman, enviar las cabeceras `Accept: application/json` y `X-Inertia: true` (así las respuestas llegan en JSON).

## Arquitectura

```
Ruta → Request (valida) → Controller → Service (lógica) → Repository (base de datos) → Model → MySQL
                                           └→ SwapiService → GET https://swapi.py4e.com/api/people/{id}/
```

```
app/
├── Http/Controllers/PersonajeController.php    recibe la petición y responde
├── Http/Requests/                              validaciones (PersonajeRequest, ImportarPersonajeRequest)
├── Services/PersonajeService.php               lógica: listar, crear, actualizar, eliminar, importar
├── Services/SwapiService.php                   consulta SWAPI y traduce los campos
├── Repositories/PersonajeRepository.php        consultas a la base de datos
├── Models/Personaje.php                        la tabla personajes
└── Console/Commands/ImportarPersonajes.php     comando para importar varios personajes
resources/js/
├── Pages/Personajes/                           Index, Create, Edit
├── Components/                                 PersonajeForm, Modal, ImportModal, Pagination, Spinner
└── Layouts/AppLayout.vue                       cabecera y mensajes
docker/backend/Dockerfile                       imagen de PHP
docker-compose.yml                              contenedores db, backend y frontend
```

## Funcionalidades Implementadas

Obligatorias:
- CRUD completo de personajes
- Integración con SWAPI
- Validaciones backend y frontend
- Paginación
- Componentes Vue reutilizables
- Diseño responsive

Bonus:
- Service Layer (`PersonajeService`, `SwapiService`)
- Repository Pattern (`PersonajeRepository`)
- Pruebas automatizadas (10 pruebas del CRUD y de la importación, con SWAPI simulado)
- Borrado lógico con el campo `estado` (equivalente a Soft Deletes)
- Comando Artisan para importar varios personajes (`personajes:importar`)
- Búsqueda en tiempo real por código o nombre
- Filtro por género
- Docker Compose
- Modal de importación con la lista de SWAPI (indica los ya importados)

## Tecnologías

- Laravel 13 (PHP 8.4)
- Vue.js 3
- Inertia.js 3
- Tailwind CSS 4
- Vite
- MySQL 8.4
- Docker

## Notas

- **Laravel 13:** el PDF pide Laravel 10+ y crear el proyecto con `laravel new`, que instala la última versión. Por eso se requiere PHP 8.3+.
- **Rutas:** se usaron nombres descriptivos (`/listaPersonajes`, `/crearPersonaje`, etc.) en lugar de `/personajes`, para que se lea claramente qué hace cada una.
- **Eliminar:** no borra el registro; cambia `estado` de 1 a 0. Los listados solo muestran los personajes con `estado = 1`. Si se vuelve a importar un personaje eliminado, se reactiva.
- **Auditoría:** la tabla tiene `usuario_creado` y `usuario_actualizado`. Como el proyecto no tiene autenticación, siempre valen 1.
- **Token CSRF:** está desactivado en las rutas de personajes para poder probarlas desde Postman. En producción se mantendría activado.
- **Datos de SWAPI:**
  - `"unknown"` en altura o masa se guarda vacío.
  - `"78.2"` se redondea a 78 y `"1,358"` se convierte a 1358.
  - Si un valor supera el máximo permitido (altura 300, masa 500), se guarda vacío. Le pasa a Jabba (masa 1358).
  - Los géneros distintos de `male` y `female` (`hermaphrodite`, `none`) se guardan como `n/a`.
  - El planeta de origen se obtiene con una segunda consulta a la URL `homeworld`.
  - El ID 17 no existe en SWAPI (responde 404), y la aplicación muestra el error.
- **URL de SWAPI:** se configura en `.env` (`SWAPI_URL`).

## Preguntas Técnicas

### 1. Organización del código backend

Separé el código en capas, cada una con una sola responsabilidad:
- **Request:** valida los datos (`PersonajeRequest`, `ImportarPersonajeRequest`).
- **Controller:** recibe la petición, llama al servicio y responde. No tiene lógica ni consultas.
- **Service:** contiene la lógica. `PersonajeService` lista, crea, actualiza, elimina e importa; `SwapiService` consulta SWAPI y traduce los campos.
- **Repository:** `PersonajeRepository` es la única capa que consulta la base de datos.
- **Model:** `Personaje`, la tabla.

Así, si mañana se necesita una API REST o una app móvil, se reutilizan el Service y el Repository y solo se agrega otro Controller.

### 2. Componentes Vue

- **`Modal`:** ventana base (título, botón cerrar y contenido). La usan el modal de importar y el de confirmar eliminación.
- **`PersonajeForm`:** el formulario con todos los campos y sus errores. Lo usan las páginas Crear y Editar; solo cambian el texto del botón y la acción.
- **`ImportModal`:** importa por ID o eligiendo de la lista de SWAPI.
- **`Pagination`:** funciona con cualquier listado paginado de Laravel.
- **`Spinner`:** indicador de carga para los botones.

Son reutilizables porque reciben los datos por props y avisan con eventos. No dependen de una página en particular.

### 3. Manejo de errores de SWAPI

- **Antes de consultar:** se valida que el ID esté entre 1 y 83, y se revisa que no esté ya importado.
- **Timeout o API caída:** `Http::timeout(10)`. Si no responde, se captura `ConnectionException` y se muestra "SWAPI no responde. Intenta de nuevo en unos minutos."
- **ID inválido (404):** se muestra "No existe un personaje con el ID X en SWAPI."
- **Otros errores de SWAPI:** se muestra un mensaje genérico.
- **El planeta:** si su consulta falla, no se cancela la importación; solo queda vacío.

Los errores se lanzan como errores de validación, así que llegan al modal igual que cualquier error de formulario. Se probaron con pruebas automatizadas que simulan SWAPI con `Http::fake()`.

### 4. Validaciones

- **Backend:** con Form Requests y mensajes en español. Es la validación que importa, porque no se puede saltar desde el navegador ni desde Postman.
- **Frontend:** atributos HTML (`required`, `minlength`, `min`, `max`), que dan aviso inmediato sin enviar el formulario.
- **Errores del backend:** se muestran debajo de cada campo gracias a `useForm()` de Inertia.

Los máximos de altura y masa están en el modelo y los usan tanto la validación como la importación de SWAPI.

### 5. Mayor dificultad

Hacer que Docker funcionara rápido en Windows. Al principio el backend tardaba minutos en arrancar y el CSS de Tailwind tardaba más de un minuto en cargar.

Lo resolví de tres formas:
- dejando `vendor` y `node_modules` en volúmenes de Docker;
- limitando las carpetas que revisa Tailwind;
- compilando el frontend con `npm run build`.

También fue un reto manejar los datos inconsistentes de SWAPI ("unknown", "1,358", géneros fuera del mapeo).

### 6. Uso de Inteligencia Artificial

Sí, usé Claude (Claude Code) como asistente durante todo el proyecto: para planificar, configurar Docker, escribir el código del backend y del frontend, las pruebas y la documentación.

Yo tomé las decisiones y revisé cada paso antes de hacer cada commit. Por ejemplo:
- el orden de trabajo (base de datos, luego API y luego UI);
- el borrado lógico con `estado`;
- los campos de auditoría;
- los nombres de rutas y métodos;
- mantener el código simple.

Validé que el código fuera correcto así:
- con las pruebas automatizadas (`php artisan test`);
- probando cada ruta en Postman;
- usando la aplicación en el navegador;
- revisando los datos en MySQL.

### 7. Mejoras futuras

- Autenticación, para guardar el usuario real en `usuario_creado` y `usuario_actualizado`.
- Volver a activar el token CSRF y ofrecer una API REST separada con tokens para clientes externos.
- Ordenar la tabla por columna.
- Guardar en caché la lista de SWAPI.
- Integración continua con GitHub Actions.

### 8. Autoevaluación

8/10. Lo mejor es la organización en capas (cada archivo tiene una sola responsabilidad), el manejo de errores de SWAPI con pruebas automatizadas y el modal de importación con la lista de SWAPI. Lo que mejoraría: mantener las rutas exactas del PDF y el token CSRF activo.

### 9. Tiempo invertido

Aproximadamente 4 horas.
