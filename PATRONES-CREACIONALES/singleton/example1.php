<?php


// PATRON SINGLETON

/* Pasos para crear el patron singleton
   - crear una clase con una propiedad privada y estatica
   - crear el constructor privado o protegido (dependiendo si vamos a heredar o no)
   - crear un método estatico para crear una
     instancia y asignarla a la propiedad
     estatica si no existe una previamente y
     devolverla o sólo devolverla

    - crear un metodo de instancia que puede tener una lógica cualquiera 
*/


class Db
{
  // esta propiedad va a guardad la instancia de la clase Db
  private static $db;
  private $conextion;

  private function __construct($conection)
  {
    debuguear('conectando por primera vez');
    $this->conextion = $conection;
  }


  // método devuelve una instancia de Db, esta será la misma instancia 
  // para todos los llamados de getInstance
  static function getInstance($connect)
  {
    if (self::$db === null) {
      self::$db = new Db($connect);
    }

    return self::$db;
  }

  public function query($query)
  {
    $res = $this->conextion->query($query);
    debuguear($res);
  }
}

$connect = new mysqli('localhost', 'root', '', 'patrones_disenio');

$newdb = Db::getInstance($connect);
debuguear($newdb);
$newdb->query("INSERT INTO singleton (name, price) VALUES('mesa', 300)");


$newdb2 = Db::getInstance($connect);
debuguear($newdb2);
