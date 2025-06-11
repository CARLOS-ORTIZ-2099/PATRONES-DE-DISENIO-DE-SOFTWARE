<?php
/* Considere una aplicación de software que necesita gestionar la creación
   de varios tipos de vehículos, como vehículos de dos, tres y cuatro
   ruedas. Cada tipo de vehículo tiene sus propias propiedades y
   comportamientos específicos. 
*/


// -------------- Patrón de diseño sin método de fábrica --------------


// Library classes
abstract class Vehicle
{
  abstract function printVehicle(): void;
}

class TwoWheeler extends Vehicle
{
  public function printVehicle(): void
  {
    debuguear("I am two wheeler");
  }
}

class FourWheeler extends Vehicle
{
  public function printVehicle(): void
  {
    debuguear("I am four wheeler");
  }
}

// Client (or user) class
class Client
{
  private Vehicle|null $pVehicle;

  public function __construct(int $type)
  {
    if ($type == 1) {
      $this->pVehicle = new TwoWheeler();
    } else if ($type == 2) {
      $this->pVehicle = new FourWheeler();
    } else {
      $this->pVehicle = null;
    }
  }

  public function cleanup(): void
  {
    if ($this->pVehicle != null) {
      $this->pVehicle = null;
    }
  }

  public function getVehicle(): Vehicle
  {
    return $this->pVehicle;
  }
}

// Driver program
class GFG
{

  public static function main(): void
  {
    $pClient = new Client(1);
    $pVehicle = $pClient->getVehicle();
    if ($pVehicle != null) {
      $pVehicle->printVehicle();
    }
    $pClient->cleanup();
  }
}

GFG::main();

// Problemas con el diseño actual
/*- La clase Client crea objetos TwoWheeler y FourWheeler directamente según
    la entrada. Esta fuerte dependencia dificulta el mantenimiento o la actualización del código. 

  - La clase Client no solo decide qué vehículo crear, sino que también 
    gestiona su ciclo de vida. Esto mezcla responsabilidades, lo cual
    contradice el principio de que una clase solo debe tener una razón para 
    cambiar.
  
  - Para añadir un nuevo tipo de vehículo, es necesario modificar la clase  
    Client, lo que dificulta escalar el diseño. Esto contradice la idea de 
    que las clases deben ser ampliables, pero no modificables.  

*/



// 2. -------------- Con el patrón de diseño del método de fábrica --------------

// 1. Interfaz del producto

// Interfaz de producto que representa un vehículo
abstract class VehiclePattern
{
  public abstract function printVehicle(): void;
}


// 2. Productos concretos


// Clases de productos concretos que representan diferentes tipos de vehículos
class TwoWheelerPattern extends VehiclePattern
{
  public function printVehicle(): void
  {
    debuguear("I am two wheeler");
  }
}

class FourWheelerPattern extends VehiclePattern
{
  public function printVehicle(): void
  {
    debuguear("I am four wheeler");
  }
}


// 3. Interfaz de creador (Interfaz de fábrica)


// Interfaz de fábrica que define el método de fábrica
interface VehicleFactory
{
  public function createVehicle(): VehiclePattern;
}


// 4. Creadores concretos 

// Clase de fábrica concreta para TwoWheeler
class TwoWheelerFactory implements VehicleFactory
{
  public function createVehicle(): VehiclePattern
  {
    return new TwoWheelerPattern();
  }
}

// Clase de fábrica concreta para FourWheeler
class FourWheelerFactory implements VehicleFactory
{
  public function createVehicle(): VehiclePattern
  {
    return new FourWheelerPattern();
  }
}



// Clase Cliente

class ClientPattern
{
  private VehiclePattern $pVehicle;

  public function __construct(VehicleFactory $fabrica)
  {
    $this->pVehicle = $fabrica->createVehicle();
  }
  public function getVehicle(): VehiclePattern
  {
    return $this->pVehicle;
  }
}

// controlador del programa
class GFGPATTERN
{


  public static function main(): void
  {
    $twoWheelerFactory = new TwoWheelerFactory;
    $twoWheelerClient = new ClientPattern($twoWheelerFactory);
    $twoWheeler = $twoWheelerClient->getVehicle();
    $twoWheeler->printVehicle();


    $fourWheelerFactory = new FourWheelerFactory;
    $fourWheelerClient = new ClientPattern($fourWheelerFactory);
    $fourWheeler = $fourWheelerClient->getVehicle();
    $fourWheeler->printVehicle();
  }
}
GFGPATTERN::main();

// Lo que se hizo

/* - VehiclePattern sirve como interfaz del producto y define el método común que todos los productos 
     concretos deben implementar. printVehicle() 


  -  TwoWheelerPattern y FourWheelerPattern son clases de productos concretos que representan diferentes
     tipos de vehículos, implementando el método. printVehicle()


  -  VehicleFactory actúa como la interfaz del Creador (Interfaz de Fábrica) con un método que representa
     el método de fábrica.createVehicle()


  -  TwoWheelerFactory y FourWheelerFactory son clases creadoras concretas que implementan la interfaz para
     crear instancias de tipos específicos de vehículos

*/
