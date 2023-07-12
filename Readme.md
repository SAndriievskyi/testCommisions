# 🐳 Docker + PHP 8.2 + MySQL + Nginx + Symfony 6.2 Boilerplate

## Description

This is a complete stack for running Symfony 6.2 into Docker containers using docker-compose tool.

It is composed by 4 containers:

- `nginx`, acting as the webserver.
- `php`, the PHP-FPM container with the 8.2 version of PHP.
- `db` which is the MySQL database container with a **MySQL 8.0** image.

## Installation

1. 😀 Clone this repo.

2. If you are working with Docker Desktop for Mac, ensure **you have enabled `VirtioFS` for your sharing implementation**. `VirtioFS` brings improved I/O performance for operations on bind mounts. Enabling VirtioFS will automatically enable Virtualization framework.

3. Create the file `./.docker/.env.nginx.local` using `./.docker/.env.nginx` as template. The value of the variable `NGINX_BACKEND_DOMAIN` is the `server_name` used in NGINX.

4. Go inside folder `./docker` and run `docker compose up -d` to start containers.

5. Inside the `php` container, run `composer install` to install dependencies from `/var/www/symfony` folder.

