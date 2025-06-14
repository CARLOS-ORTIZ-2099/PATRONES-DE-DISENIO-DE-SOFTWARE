<?php


// fabrica
// la fabriac se encarga de crera objetos, esta se heredara a otras subclases
// y cada una de ellas hara su propia implementación del método de creación
// pero debemos tener en cuenta que la labro principal de la fabrica no es 
// sólo crear objetos, si no también realizar lógica relacionada a dichos 
// objetos creados.  
abstract class FactoryVehiculo
{

  abstract function createVehiculo($params = []): VehiculoInterface;

  public function showMessage()
  {
    debuguear("ejecutando método");
  }
}

// fabricas concretas

class FactoryMoto extends FactoryVehiculo
{
  function createVehiculo($params = []): VehiculoInterface
  {
    extract($params);
    return new Moto($llantas, $marca, $color, $tipo, $tipoBrevete, $isLineal);
  }
}


class FactoryAuto extends FactoryVehiculo
{
  function createVehiculo($params = []): VehiculoInterface
  {
    extract($params);
    return new Carro($llantas, $marca, $color, $tipo, $tipoBrevete, $tamanioMaletera, $tipoDeCambio);
  }
}


class FactoryCamion extends FactoryVehiculo
{
  function createVehiculo($params = []): VehiculoInterface
  {
    extract($params);
    return new Camion($llantas, $marca, $color, $tipo, $tipoBrevete, $tipoDeCambio);
  }
}


// producto 
abstract class VehiculoInterface
{
  public $cantidad_llantas;
  public $marca;
  public $color;
  public $tipo;
  public $tipoBrevete;


  public function __construct($cantidad_llantas, $marca, $color, $tipo, $tipoBrevete,)
  {
    $this->cantidad_llantas = $cantidad_llantas;
    $this->marca = $marca;
    $this->color = $color;
    $this->tipo = $tipo;
    $this->tipoBrevete = $tipoBrevete;
  }

  abstract public function getTipo();
  abstract public function getDescripcion();
}


// productos concretos
class Moto  extends VehiculoInterface
{
  public $isLineal;

  public function __construct($a, $b, $c, $d, $e, $f)
  {
    $this->isLineal = $f;
    parent::__construct($a, $b, $c, $d, $e);
  }
  public function getTipo()
  {
    debuguear($this->tipo);
  }
  public function getDescripcion()
  {
    debuguear("esta es una moto de color : " . $this->color);
  }
}

class Carro extends VehiculoInterface
{
  public $tamanioMaletera;
  public $tipoDeCambio;
  public function __construct($a, $b, $c, $d, $e, $f, $g)
  {
    $this->tamanioMaletera = $f;
    $this->tipoDeCambio = $g;
    parent::__construct($a, $b, $c, $d, $e);
  }
  public function getTipo()
  {
    debuguear($this->tipo);
  }
  public function getDescripcion()
  {
    debuguear("este es un carro de color : " . $this->color);
  }
}


class Camion extends VehiculoInterface
{
  public $tipoDeCambio;
  public function __construct($a, $b, $c, $d, $e, $f)
  {
    $this->tipoDeCambio = $f;
    parent::__construct($a, $b, $c, $d, $e);
  }
  public function getTipo()
  {
    debuguear($this->tipo);
  }
  public function getDescripcion()
  {
    debuguear("este es un camion de color : " . $this->color);
  }
}


// motos

$motos = new FactoryMoto;

$moto1 = $motos->createVehiculo([
  'llantas' => 2,
  'marca' => 'susuki',
  'color' => 'azul',
  'tipo' => 'moto',
  'tipoBrevete' => 'a2',
  'isLineal' => true
]);

$moto2 = $motos->createVehiculo([
  'llantas' => 2,
  'marca' => 'yokohama',
  'color' => 'rojo',
  'tipo' => 'moto',
  'tipoBrevete' => 'a2',
  'isLineal' => false
]);


debuguear($moto1);
$moto1->getDescripcion();
debuguear($moto2);
$moto2->getDescripcion();


// carros

$carros = new FactoryAuto;

$carro1 = $carros->createVehiculo(
  [
    'llantas' => 4,
    'marca' => 'subaru',
    'color' => 'negro',
    'tipo' => 'carro',
    'tipoBrevete' => 'a1',
    'tamanioMaletera' => 'grande',
    'tipoDeCambio' => 'automatico'
  ]
);

$carro2 = $carros->createVehiculo(
  [
    'llantas' => 4,
    'marca' => 'chevrolet',
    'color' => 'azul',
    'tipo' => 'carro',
    'tipoBrevete' => 'a1',
    'tamanioMaletera' => 'pequeño',
    'tipoDeCambio' => 'mecanico'
  ]
);

debuguear($carro1);
$carro1->getDescripcion();
debuguear($carro2);
$carro2->getDescripcion();


// camiones
$camiones = new FactoryCamion;
$camion1 = $camiones->createVehiculo([
  'llantas' => 4,
  'marca' => 'chevrolet',
  'color' => 'azul',
  'tipo' => 'carro',
  'tipoBrevete' => 'a1',
  'tipoDeCambio' => 'mecanico'
]);


debuguear($camion1);
$camion1->getDescripcion();
