<?php


// PATRON SINGLETON

/* La clase Singleton define el método `GetInstance` que sirve como
   alternativa al constructor y permite a los clientes acceder a la misma
   instancia de esta clase una y otra vez. 
*/


// ejemplo de singleton con subclases que solo puedan tener una instancia
class Singleton
{

  // arreglo que guardara las instancias unicas de la clase Singleton e 
  // instancias únicas de subclases de Singleton
  private static $instances = [];

  protected function __construct()
  {
    debuguear("ejecutando instancia de " . static::class);
  }


  protected function __clone() {}


  public function __wakeup()
  {
    throw new \Exception("Cannot unserialize a singleton.");
  }

  /* 
     Esta implementación permite crear una subclase de la clase Singleton
     manteniendo solo una instancia de cada subclase.
  */

  public static function getInstance(): Singleton
  {
    /* Recordemos que static hace referencia de la clase desde donde se 
       llama al método, mientras que self hace referencia a la clase desde
       donde se define al método 
    */
    $cls = static::class;
    debuguear($cls);
    if (!isset(self::$instances[$cls])) {
      // creamos instancias dinamicamente para las propiedades del arreglo
      self::$instances[$cls] = new static();
    }
    debuguear(self::$instances);
    return self::$instances[$cls];
  }

  public function someBusinessLogic() {}
}



// ejemplo con herencia
class SingletonChild extends Singleton {}



function clientCode()
{
  $s1 = Singleton::getInstance();
  $s2 = Singleton::getInstance();
  $s3 = SingletonChild::getInstance();
  if ($s1 === $s2) {
    echo "Singleton funciona, ambas variables contienen la misma instancia..";
  } else {
    echo "Singleton falló, las variables contienen instancias diferentes.";
  }
}

clientCode();
