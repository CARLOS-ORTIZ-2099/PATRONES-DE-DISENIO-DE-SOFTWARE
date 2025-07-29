<?php

/* La clase abstracta define un método de plantilla que contiene el 
   esqueleto de algún algoritmo, compuesto por llamadas a operaciones 
   primitivas (generalmente) abstractas.
   Las subclases concretas deben implementar estas operaciones, pero 
   dejando el método de plantilla intacto.
*/
abstract class AbstractClass
{
  // El método de plantilla define el esqueleto de un algoritmo.
  // y debe ser del tipo final para no ser sobreescrita
  final public function templateMethod(): void
  {
    $this->baseOperation1();
    $this->requiredOperations1();
    $this->baseOperation2();
    $this->hook1();
    $this->requiredOperation2();
    $this->baseOperation3();
    $this->hook2();
  }

  // Estas operaciones ya tienen implementaciones.
  protected function baseOperation1(): void
  {
    debuguear("AbstractClass dice: Estoy haciendo la mayor parte del trabajo.");
  }

  protected function baseOperation2(): void
  {
    debuguear("AbstractClass dice: Pero dejo que las subclases anulen algunas operaciones");
  }

  protected function baseOperation3(): void
  {
    debuguear("AbstractClass dice: Pero de todos modos estoy haciendo la mayor parte del trabajo.");
  }

  // Estas operaciones deben implementarse en subclases.
  abstract protected function requiredOperations1(): void;

  abstract protected function requiredOperation2(): void;

  /* Estos son "ganchos". Las subclases pueden sobrescribirlos, pero no es 
     obligatorio, ya que los ganchos ya tienen una implementación 
     predeterminada (aunque vacía). Los ganchos proporcionan puntos de 
     extensión adicionales en algunos puntos cruciales del algoritmo.
  */
  protected function hook1(): void {}

  protected function hook2(): void {}
}

/* Las clases concretas deben implementar todas las operaciones abstractas 
   de la clase base.
   También pueden anular algunas operaciones con una implementación 
   predeterminada. 
*/
class ConcreteClass1 extends AbstractClass
{
  protected function requiredOperations1(): void
  {
    debuguear("ConcreteClass1 dice: Operación implementada1");
  }

  protected function requiredOperation2(): void
  {
    debuguear("ConcreteClass1 dice: Operación implementada2");
  }
}

/* Generalmente, las clases concretas anulan solo una fracción de las 
   operaciones de la clase base.
*/
class ConcreteClass2 extends AbstractClass
{
  protected function requiredOperations1(): void
  {
    debuguear("ConcreteClass2 dice: Operación implementada1");
  }

  protected function requiredOperation2(): void
  {
    debuguear("ConcreteClass2 dice: Operación implementada2");
  }

  protected function hook1(): void
  {
    debuguear("ConcreteClass2 dice: Hook1 anulado");
  }
}

/* El código del cliente llama al método de plantilla para ejecutar el 
   algoritmo. El código del cliente no necesita conocer la clase concreta 
   del objeto con el que trabaja, siempre que trabaje con objetos a través 
   de la interfaz de su clase base.
*/
function clientCode(AbstractClass $class)
{
  $class->templateMethod();
}

debuguear("El mismo código de cliente puede funcionar con diferentes subclases:");
clientCode(new ConcreteClass1());

debuguear("El mismo código de cliente puede funcionar con diferentes subclases:");
clientCode(new ConcreteClass2());
