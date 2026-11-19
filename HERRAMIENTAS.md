# Comandos usados

## Paso 0: Repositorio
git clone https://github.com/KPuchoc/prueba_tecnica.git  -> descarga el repositorio de GitHub

## Paso 1: Proyecto Laravel
composer global require laravel/installer  -> instala el comando laravel
laravel new prueba_tecnica                 -> crea el proyecto

## Paso 2: Docker
docker compose up -d --build  -> construye las imágenes y levanta los contenedores
docker compose ps             -> muestra los contenedores y su estado
docker compose logs backend   -> muestra los mensajes del backend
docker compose down           -> apaga los contenedores

## Referencias
https://hub.docker.com/_/php       -> imagen de PHP (Dockerfile del backend)
https://hub.docker.com/_/mysql     -> imagen de MySQL (contenedor db)
https://hub.docker.com/_/node      -> imagen de Node (contenedor frontend)
https://hub.docker.com/_/composer  -> imagen de Composer (se copia en el Dockerfile)
https://docs.docker.com/compose/   -> documentación de Docker Compose
