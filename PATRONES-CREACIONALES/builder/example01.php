<?php



/* El patron builder es del tipo creacional y sirve para crear objetos complejos paso a paso
   su utilidad radica en poder crear varios tipos y/o representaciones de un objeto, sin 
   la necesidad de crear subclases y/o constructores demasiados cargados de parametros.
   La idea es básicamente :  separar la construcción de un objeto de su representación
   para así evitar tener que pasar muchos parametros en la instancia de dicha clase y muchos 
   de ellos quizas no se utilizen, tambien para evitar la sobrecarga de métodos constructores
   (sólo en lenguajes que soportan la sobrecarga) 
*/





/* Pasos para crear el patron builder  

 - crear una interface que contenga métodos comunes entre todos los objetos relacionados.
 
 - crear clase "constructoras" que implementen esta interface cada clase "constructora" tendra 
 su propia implementacion de la interface, aquí es donde ejecutaremos la construccion de este 
 objeto.

 - Una clase directora : esta se encargara de gestionar el orden de como se crearan los objetos,
 asi como sus subtipos(aunque esta clase podría ser opcional).

 - objeto : estos son los productos finales de la construcción.


*/



/* Por ejemplo nos piden crear personajes para un videojuego con poo en php estos personajes varian 
   segun su tipo o categoria : común, raro, legendario
*/


// ejemplo sin usar una interface
debuguear("------------ Ejemplo sin interface ------------");


class Character
{
  public string $name;
  public string $type;
  public int $healt;
  public array $powers = [];
  public array $skin = [];

  public function show()
  {
    return [
      "name" => $this->name,
      "type" => $this->type,
      "healt" => $this->healt,
      "powers" => $this->powers,
      "skin" => $this->skin
    ];
  }
  public function nuevometodo() {}
}

class CharacterBuilder
{
  private Character $character;

  public function __construct()
  {
    $this->reset();
  }

  public function reset()
  {
    $this->character = new Character();
  }

  public function name(string $value): self
  {
    $this->character->name = $value;
    return $this;
  }

  public function type(string $value): self
  {
    $this->character->type = $value;
    return $this;
  }

  public function healt(int $value): self
  {
    $this->character->healt = $value;
    return $this;
  }

  public function powers(array $value): self
  {
    $this->character->powers = $value;
    return $this;
  }

  public function skin(array $value): self
  {
    $this->character->skin = $value;
    return $this;
  }



  public function build(): Character
  {
    $character = $this->character;
    $this->reset();
    return $character;
  }
}

// Uso del patrón Builder
$builder = new CharacterBuilder();
$character1 = $builder->name("pj1")->type("comun")->healt(1700)->powers(["super fuerza"])->build();
$character2 = $builder->name("pj1")->type("raro")->healt(1900)->powers(["super fuerza", "super velocidad", "resistencia"])->skin(["azul", "rojo", "dorado"])->build();
$character3 = $builder->name("pj3")->type("legendario")->healt(3000)->powers(["super fuerza", "super velocidad", "resistencia", "inteligencia"])->skin(["azul", "rojo", "dorado"])->build();

debuguear($character1->show());
debuguear($character2->show());
debuguear($character3->show());



// ejemplo usando una interface

debuguear("------------ Ejemplo con interface ------------");

// Definiendo la interfaz VehicleBuilder
// Todos los builders deben implementar estos métodos, asegurando que la construcción siga un estándar.
interface VehicleBuilder
{
  public function setType(string $type): self;
  public function setWheels(int $wheels): self;
  public function setEngine(string $engine): self;
  public function build(): Vehicle;
}


// Definiendo la clase Vehicle (objeto construido)
// Este es el objeto final (Producto), construido por cualquier builder.
class Vehicle
{
  public string $type;
  public int $wheels;
  public string $engine;

  public function show()
  {
    return [
      "type" => $this->type,
      "wheels" => $this->wheels,
      "engine" => $this->engine
    ];
  }
}


// Creando una implementación concreta CarBuilder
// CarBuilder sigue la estructura de VehicleBuilder, asegurando que cumpla con todos los métodos requeridos.
class CarBuilder implements VehicleBuilder
{
  private Vehicle $vehicle;

  public function __construct()
  {
    $this->reset();
  }

  public function reset()
  {
    $this->vehicle = new Vehicle();
  }

  public function setType(string $type): self
  {
    $this->vehicle->type = $type;
    return $this;
  }

  public function setWheels(int $wheels): self
  {
    $this->vehicle->wheels = $wheels;
    return $this;
  }

  public function setEngine(string $engine): self
  {
    $this->vehicle->engine = $engine;
    return $this;
  }

  public function build(): Vehicle
  {
    $vehicle = $this->vehicle;
    $this->reset();
    return $vehicle;
  }
}

class MotorcycleBuilder implements VehicleBuilder
{
  private Vehicle $vehicle;

  public function __construct()
  {
    $this->reset();
  }

  public function reset()
  {
    $this->vehicle = new Vehicle();
  }

  public function setType(string $type): self
  {
    $this->vehicle->type = $type;
    return $this;
  }

  public function setWheels(int $wheels): self
  {
    $this->vehicle->wheels = $wheels;
    return $this;
  }

  public function setEngine(string $engine): self
  {
    $this->vehicle->engine = $engine;
    return $this;
  }

  public function build(): Vehicle
  {
    $vehicle = $this->vehicle;
    $this->reset();
    return $vehicle;
  }
}


// Uso del Builder con encadenamiento
$builder = new CarBuilder();

$car = $builder->setType('Sedan')->setWheels(4)->setEngine('V6')->build();
debuguear($builder);
debuguear($car);

$builder2 = new MotorcycleBuilder();

$cicle = $builder2->setType('Nakasaky')->setWheels(2)->setEngine('V1')->build();
debuguear($builder2);
debuguear($cicle);
