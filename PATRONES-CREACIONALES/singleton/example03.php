<?php


/* Si necesita admitir varios tipos de Singletons en su aplicación, puede:
   definir las características básicas del Singleton en una clase base,
   mientras traslada la lógica de negocio real (como el registro) a
   subclases. 
*/

class Singleton
{

  /* La instancia del singleton real casi siempre reside dentro de un campo
     estático. En este caso, el campo estático es una matriz, donde cada 
     subclase del singleton almacena su propia instancia. 
  */

  private static $instances = [];


  /* El constructor de Singleton no debe ser público. Sin embargo, tampoco
     puede ser privado si queremos permitir la subclasificación. 
  */
  protected function __construct() {}



  /* No se permiten la clonación ni la deserialización de singletons. */
  protected function __clone() {}

  public function __wakeup()
  {
    throw new \Exception("Cannot unserialize singleton");
  }


  /* El método que utiliza para obtener la instancia Singleton. */
  public static function getInstance()
  {
    $subclass = static::class;
    //debuguear($subclass);
    //debuguear(self::$instances);
    if (!isset(self::$instances[$subclass])) {
      /* Tenga en cuenta que aquí usamos la palabra clave "static" en lugar 
         del nombre de la clase. En este contexto, la palabra clave "static" 
         significa "el nombre de la clase actual". Este detalle es importante
         porque, al llamar al método(getInstance) en la subclase, queremos que se cree aquí
         una instancia de esa subclase. 
      */

      self::$instances[$subclass] = new static();
    }
    debuguear(self::$instances);
    return self::$instances[$subclass];
  }
}



/* La clase de registro es el uso más conocido y elogiado del patrón Singleton.
   En la mayoría de los casos, se necesita un único objeto de registro que escriba 
   en un único archivo de registro (control sobre el recurso compartido). También 
   se necesita una forma conveniente de acceder a esa instancia desde cualquier 
   contexto de la aplicación (punto de acceso global). 
*/
class Logger extends Singleton
{
  /* Un recurso de puntero de archivo del archivo de registro. */
  private $fileHandle;

  /* Dado que el constructor de Singleton se llama solo una vez, solo se abre un único
     recurso de archivo en todo momento.
     Tenga en cuenta que, para simplificar, abrimos el flujo de la consola en lugar del
     archivo real. 
  */

  protected function __construct()
  {
    debuguear("abriendo la consola para escribir");
    // aqui asignamos a nuestra variable fileHandle un stream como valor
    // fopen devuelve un puntero al archivo abierto
    $this->fileHandle = fopen('php://stdout', 'w');
  }

  /* Escribe una entrada de registro en el recurso de archivo abierto. */
  public function writeLog(string $message): void
  {
    $date = date('Y-m-d');
    // fwrite escribe en un archivo pasandole el puntero y el mensaje
    fwrite($this->fileHandle, "$date: $message\n ");
  }

  /* Un atajo práctico para reducir la cantidad de código necesario para registrar mensajes
     desde el código del cliente. 
  */
  public static function log(string $message): void
  {
    // el método getInstance devuelve una instancia de la clase que llame al método log
    $logger = static::getInstance();
    // lusego ejecutamos el método writeLog de dicha instancia
    $logger->writeLog($message);
  }
}



/* Aplicar el patrón Singleton al almacenamiento de configuración también es una práctica común.
   A menudo, es necesario acceder a las configuraciones de la aplicación desde diferentes partes
   del programa. Singleton ofrece esa comodidad. 
*/

class Config extends Singleton
{
  private $hashmap = [];

  public function getValue(string $key): string
  {
    return $this->hashmap[$key];
  }

  public function setValue(string $key, string $value): void
  {
    $this->hashmap[$key] = $value;
  }
}


// Ejemplo con Loger
Logger::log("Started!");

// Comparar valores del singleton de Logger.
// getInstance devuelve instancias de los clases que llaman al método
$l1 = Logger::getInstance();
$l2 = Logger::getInstance();
if ($l1 === $l2) {
  Logger::log("Logger tiene una única instancia.");
} else {
  Logger::log("Los Loggers son diferentes.");
}


// Ejemplo con Config

// Compruebe cómo Config singleton guarda los datos...
// getInstance devuelve instancias de los clases que llaman al método
$config1 = Config::getInstance();
$login = "test_login";
$password = "test_password";
$config1->setValue("login", $login);
$config1->setValue("password", $password);
// ...y lo restaura.
debuguear($config1);
$config2 = Config::getInstance();
if (
  $login == $config2->getValue("login") &&
  $password == $config2->getValue("password")
) {
  Logger::log("La configuración singleton también funciona bien.");
}

Logger::log("¡Finalizado!");
