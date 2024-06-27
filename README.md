# Pizzeria API - README

## Initial Configuration

En la raíz del proyecto tenemos un `.env.example` el cuál lo copiamos y creamos con el nombre `.env`.

```bash
$ cp .env.example .env
```

## Installation

El proyecto esta creado en un entorno dockerizado. Una vez clonado para ponerlo en marcha tenemos que tener instalado en nuestra máquina las siguientes dependencias:

- `make`
- `docker`
- `docker-compose`

## Configuración de Docker

Para generar las imágenes ejecutamos:

```bash
$ make up
```

Para detener y volver a restaurar los contenedores ejecutamos el siguiente comando:

```bash
$ make down && make prune && make up
```

Para usar `composer` y `php` desde la consola del contenedor de php:

## Laravel

```bash
$ make shell php

$ php -v
$ composer -v

# Actualizamos permisos
$ chmod -R 775 storage/*


$ composer install

# APP token si no se crea al momento:
$ php artisan key:generate


$ php artisan route:clear

$ php artisan cache:clear

$ php artisan view:clear

# To exit the container shell use the command:
$ exit
```

## Accesos de la aplicación:

[Aplicación de Laravel](http://localhost:9000/)

[phpMyAdmin](http://localhost:7010/)

[MailHog](http://localhost:8025/)
