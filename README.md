# login-memory-persistent-8

***         
Ejercicio inicial: El objetivo de la prueba es desarrollar una aplicación en PHP, sin UI, capaz de solucionar el problema usando persistencia en memoria.

Desarrolla un sistema tolerante a errores responsable de comprobar el login y password de una lista usuarios con las siguientes condiciones:
- Devuelva si un usuario existe en el sistema o no.
- Devuelve si el usuario/password coinciden o no.

Añade la cobertura de tests que consideres necesaria para validar el funcionamiento de la aplicación.

Ejercicio bonus: El objetivo de la prueba es desarrollar solo el juego de tests (unitarios, funcionales o aceptación), sin implementación, con la idea de que un/a tercer/a desarrollador/a pudiera implementar la solución sólo leyendo los tests.

En el sistema anterior, añade un nuevo caso de uso:

- Si el usuario/password es correcto, cambiar password del mismo usuario (utilizando el mecanismo de autenticación que considere oportuno).
***   
- Se eleva la versión de PHP a la 8.2
- Se separa la lógica de password, internamente solo se comprueba con hashed, en el repositorio existe un encode para almacenar el password cifrado
- Se está utilizando un encoding base64 únicamente a modo de ejercicio se podría usar el cifrado que se requiriese
- El decode del password se usa únicamente para generar un value object de tipo Password, para que en la lógica de la aplicación siempre se use el hash del mismo.

- Para arrancar el contenedor de Docker usar el comando:
```
docker-compose up --detach --force-recreate --build --remove-orphans
```
- Para ejecutar los tests dentro del contenedor de Docker:
```
docker-compose run --rm --no-deps php /bin/sh -c "./vendor/bin/phpunit"
```  
- Para detener el contenedor de Docker:
```
docker-compose stop
```  

- Se incluye también un fichero Makefile para tener disponible en linux una lista de comandos rápìdos:
```
make help
```