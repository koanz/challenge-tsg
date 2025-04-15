# Desafío técnico TSG

Desarrollar una aplicación (API REST) Laravel que permita a los usuarios registrarse, iniciar sesión, crear, leer, actualizar y eliminar posts. Cada post debe estar asociado a un usuario. La consulta de posts debe requerir un token de autenticación, obtenido después de realizar el login.

## Tecnologías utilizadas

- PHP 8.3.2
- Laravel 12
- PostgreSQL 12
- Composer 2.8.8

## Instalación

1. Clonar el repositorio:

```bash
git clone https://github.com/koanz/challenge-tsg
```
2. Situarse en el directorio del proyecto y ejecutar la descarga e instalación de las dependencias con composer:

```bash
composer install
```
3. Crear una copia del archivo .env.example y renombrarlo a .env

```bash
cp .env.example .env
```
4. Generar la llave de la aplicación.

```bash
php artisan key:generate
```
5. Generar la llave JWT para el acceso posterior a la app.

```bash
php artisan jwt:secret
```
6. Configurar la base de datos en el archivo .env (ejemplo).

```bash
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=challenge_tsg
DB_USERNAME=postgres
DB_PASSWORD=1234
```
7. Ejecutar el comando para generar las tablas y popular la base de datos.

```bash
php artisan migrate:fresh --seed
```
8. Levantar la aplicación.

```bash
php artisan serve
```

## Acceso local a la documentación por Swagger
```bash
http://127.0.0.1:8000/api/documentation
```
