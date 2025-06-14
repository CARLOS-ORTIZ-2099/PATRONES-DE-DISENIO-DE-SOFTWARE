<?php

// prototipo
// la fabrica general de camisetas
abstract class Camiseta
{
  protected string $nombre;
  protected int $talla;
  protected string $color;
  protected string $manga;
  protected string $estampado;
  protected  $material;


  public function __construct(string $nombre, int $talla, string $color, string $manga, string $estampado,  $material)
  {
    $this->nombre = $nombre;
    $this->talla = $talla;
    $this->color = $color;
    $this->manga = $manga;
    $this->estampado = $estampado;
    $this->material = $material;
  }

  public abstract function clone(): Camiseta;

  public function getValue($key)
  {
    return $this->$key;
  }

  public function setValue($key, $value)
  {
    if (isset($this->$key)) {
      $this->$key = $value;
    }
  }
}


// prototipo concreto 1
class CamisetaMCorta extends Camiseta
{

  public function __construct(int $talla, string $color, string $estampado)
  {
    $this->nombre = "Prototipo";
    $this->talla = $talla;
    $this->color = $color;
    $this->manga = "Corta";
    $this->estampado = $estampado;
    $this->material = new stdClass();
  }
  public function clone(): Camiseta
  {
    return new CamisetaMCorta($this->talla, $this->color, $this->estampado);
  }
}

// prototipo concreto 2
class CamisetaMLarga extends Camiseta
{
  public function __construct(int $talla, string $color, string $estampado)
  {
    $this->nombre = "Prototipo";
    $this->talla = $talla;
    $this->color = $color;
    $this->manga = "Larga";
    $this->estampado = $estampado;
    $this->material = new stdClass();
  }
  public function clone(): Camiseta
  {
    return new CamisetaMLarga($this->talla, $this->color, $this->estampado);
  }
}

// vamos a crear objetos a partir de objetos preexistentes(prototipos) esto
// ahorra la necesidad de crearlos desde cero cuando ya existe una 
// configuración similra en el sistema


// la primera ves tenemos que crear los objetos que eventualmente serviran cómo prototipos


// Creamos los prototipos
$prototipoMCorta = new CamisetaMCorta(30, "blanco", "Logotipo");
$prototipoMLarga = new CamisetaMLarga(40, "blanco", "Logotipo");

debuguear($prototipoMCorta);
//debuguear($prototipoMLarga);


// Almacenamos las camisetas disponibles
$camisetas = [];
$args = ["montaña", "casa", "carro", "avion", "libro"];
$sizes = [10, 20, 30, 40, 50];

for ($i = 0; $i < count($args); $i++) {
  // clonando las camisetas manga cortas
  $cc =  $prototipoMCorta->clone();
  $cc->setValue("estampado", $args[$i]);
  $cc->setValue("talla", $sizes[$i]);
  //debuguear($cc);
  $camisetas[] = $cc;


  // clonando las camisetas manga larga
  $cl = $prototipoMLarga->clone();
  $cl->setValue("estampado", $args[$i]);
  $cl->setValue("talla", $sizes[$i]);
  //debuguear($cl);
  $camisetas[] = $cl;
}


debuguear($camisetas);
