# evaluacion_ciudadana
Permite a los beneficiarios de un programa presupuestario opinar fuera del esquema institucional.
Se puede acceder a la versión oficial del sitio en: http://tuevaluas.com.mx


## Guía de instalación

### Cosas necesarias (Leland Gaunt)
0. La maroma esta funciona con PHP y MySQL
1. descargar e instalar la DB: https://www.dropbox.com/s/vyb2zirb8q3camy/agentes.sql.zip?dl=0
2. tener la librería mcrypt para PHP
3. tener una cuenta de MAILGUN para los correos transaccionales
4. tener composer para las librerías de PHP
5. tener Bower para las librerías de JS

### Configuración
* En el directorio raíz del proyecto, hay que crear un archivo llamado .keys.php (está disponible un archivo llamado example.keys.php con la estructura del array de configuración). Este archivo debe contener lo siguiente:
$ci_keys = [
  'encryption_key'     => 'aquí-la-llave-de-encriptación',
  'mailgun_api_key'    => 'aquí-el-api-key-de-mailgun',
  'mailgun_public_key' => 'aquí-la-clave-pública-de-mailgun',
  'mailgun_domain'     => 'aquí-el-dominio-de-mailgun',
  'mailgun_basepath'   => 'https://api.mailgun.net/v2'
];

la 'encryption_key' debe ser una cadena de texto estilo trabalenguas, como las que se generan aquí: http://randomkeygen.com

* agregar los datos de conexión a la DB en application/config/database.php
* descargar las librerías de JS en html/js mediante: bower update
* descargar las librerías de PHP en el directorio raíz mediante composer update

* Cuando el sistema funcione, hay que crear al primer admin accediendo al siguiente URL: /index.php/better_call_arturo. Esto gener una admin con los siguientes datos:
email: arturo.cordoba@gmail.com
pass: 12345678

Una vez creado el administrador, se recomienda deshabilitar en application/config/config.php la variable "better_call_saul", para evitar que se pueda generar de nuevo al primer admin. (Si el admin existe, la URL redirige al login). También es recomendable crear un nuevo administrador con un correo real. 
