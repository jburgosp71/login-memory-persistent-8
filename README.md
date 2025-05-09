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

***
Añadir Verificación 2FA: Email - Google

- Para pseudo implementar esta funcionalidad se ha partido de un interface Verify2FAInterface que dado un usuario y el código introducido nos lo verificaría
- Este interface es usado por una factory para generar el servicio 2FA para la verificación según el tipo escogido por el usuario, esto nos permitiría fácilmente
  incluir nuevos métodos de verificación sin tener que modificar la lógica de la aplicación 
- También se generó un valueObject TwoFactoryType para tener toda la lógica de los tipos válidos de verificación 2FA en un sólo lugar
- Se creó un nuevo caso de uso, CheckLoginWith2FA, para mediante inversión de dependencias usar los servicios de verificación 2FA despues de la comprobación del usuario en el repositorio
- Al ser un ejercicio completamente sin UI se han obviado ciertos flujos y repositorios. Por ejemplo en la verificación 2FA por email no se ha generado el repositorio para almacenar 
  temporalmente los códigos que recibiría el usuario. Este es un caso típico de uso de Redis, para generar keys con un tiempo de expiración como indico en los comentarios.
- También los servicios de verificación tanto de email como de Google se usan como si todo ocurriese en el mismo momento. Pero a modo de ejercicio sirve para ver la disposición de los elementos
  principales que se harían servir.
- Se incluyen también los tests tanto de los servicios 2FA como del caso de uso.
- Para simular la verificación por Google Authenticator se ha usado la dependencia: composer require pragmarx/google2fa
- Dicha dependencia se usa para generar el Secret del user: $google2fa->generateSecretKey() y a la hora de implementar la UI se usaría para generar el QR que debería usar
  el usuario en la app de su móvil:
  ``` 
  $qrCodeUrl = $google2fa->getQRCodeUrl(
    'NombreDeTuApp',
    $usuarioEmail,
    $secret
  );
  ```