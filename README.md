# dozmaz365
Sistema de pronósticos deportivos

## Características
* Solo los usuarios registrados tienen acceso a la información del sistema.
* Un usuario puede modificar sus pronósticos hasta la hora de inicio del partido.
* Para los usuarios que no registren sus pronósticos por defecto se registrará como 0 - 0
* luego de concluido el partido el administrador del sistema debe registrar el resultado del partido, en este momento se calcularán y registrarán los puntos obtenidos.

## Reglas para la obtención de puntos
* No se puede obtener más de 5 puntos por partido
* Si el pronóstico coincide de manera exacta con el resultado oficial del partido se obtienen 5 puntos
* Sin importar los goles si se acierta al ganador o empate se obtienen 3 puntos
* Si se acierta en la cantidad de goles sin importar que equipo ganó se obtiene 1 punto
 
## Instalación

## Requisitos 
Este sistema fue desarrollado y probado con:
* Servidor Apache 2
* PHP 5.5.33
* Mysql 5.5 o MariaDB 10.1.10
* Phalcon 2.0.1
* Bootstrap v3.3.6
* Font Awesome 4.6.1
* jQuery v2.2.3
* Validator v1.1.0
* iCheck plugin Flat skin
* Gentelella Bootstrap Admin Template
* Mozilla Firefox 46.0.1

## Base de datos
* Crear una base de datos en el servidor Mysql con el nombre "dozmaz365"
* Crear un usuario de base de datos que solo tenga acceso a esta base de datos:

"GRANT ALL PRIVILEGES ON dozmaz365.* To 'admin365'@'localhost' IDENTIFIED BY '.#20-My-Sql-16.#';" 

(Cambiar la contraseña antes de ejecutar la instrucción sql)
* Restaurar el backup contenido en la carpeta schema
 
## Configuración del sistema
* Editar el archivo app/config/config.php y modificar los datos de acceso a la base de datos y el servidor LDAP para integración de los usuarios
* Si se integrará la autenticación de usuarios con un servidor LDAP editar el archivo y cambiar "$this->auth->check(array(" por "$this->auth->checkLdap(array("
* El usuario administrador por defecto es "adminDozmaz" y la contraseña "123456789"

## Licencia
Sistema de pronosticos by Guido Cutipa Yujra is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 4.0 Internacional License http://creativecommons.org/licenses/by-nc-sa/4.0/
Creado a partir de la obra en https://github.com/dozmaz/dozmaz365.
