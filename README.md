# TusCódigosQR

(At the moment this app is only available in Spanish)

TusCódigosQR es una aplicación web que permite la creación de códigos QR dinámicos y mantener un registro de estos. Este repositorio contiene el backend (API REST). Para el frontend, ver [QRServer-web](https://github.com/mesacarlos/QRServer-web).

La aplicación permitirá a los usuarios del sistema registrar una nueva cuenta en el sitio web y, una vez iniciado en sesión, el usuario podrá crear códigos QR, así como direcciones web acortadas cuyo destino sea una tercera dirección web a elección del usuario. Una vez creado, el usuario podrá modificar el destino del código QR sin ser necesaria la modificación de la imagen de código QR.
Además, la aplicación mostrará información al usuario como el número de accesos, el dispositivo utilizado para acceder al QR, el idioma y el sistema operativo, entre otros. Además, se permitirá personalizar la imagen generada del código QR incluyendo elementos como un logotipo a elección del usuario, el estilo, el tamaño y los colores.

## Instalación

TusCódigosQR necesita el siguiente software para funcionar:

* Apache
* PHP 8.0
* MySQL
* Imagick
* Extensiones de PHP necesarias: php-pdo, php-mysql, php-mbstring, php-gd, php-imagick, php8.0-xml

```sh
sudo add-apt-repository ppa:ondrej/php
sudo apt install apache2 mariadb-server php8.0 php8.0-cli php-gd phpimagick php-mysql php-mbstring php8.0-xml
```

A continuación, crea una nueva base de datos y un usuario en la base de datos:

```sh
sudo mysql
CREATE DATABASE qr_db;
CREATE USER 'myUser'@'localhost' IDENTIFIED BY 'db-pw0rd';
GRANT ALL PRIVILEGES ON *.* TO 'myUser'@'localhost' IDENTIFIED BY 'dbpw0rd';
```

A continuación, instala [Composer](https://getcomposer.org/download/) y instala las dependencias del proyecto

```sh
composer install
```

Crea un fichero .env a partir del fichero .env.example proporcionado y completalo con la información de conexión con la base de datos y con el servidor SMTP, así como las direcciones web del proyecto y la URL donde se encuentra desplegada el frontend. Por ultimo, genera una cadena de 32 caracteres alfanumericos y indícala en APP_KEY.

A continuación, genera las tablas en la base de datos ejecutando las migraciones:

```sh
php artisan migrate
```

Por último, instala browscap en tu instancia de PHP.
