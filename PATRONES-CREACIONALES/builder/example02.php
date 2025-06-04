<?php

/* La interfaz Builder especifica métodos para crear las diferentes partes
   de los objetos del producto. 
*/

interface Builder
{
  public function producePartA(): void;

  public function producePartB(): void;

  public function producePartC(): void;
}


/* Las clases de Concrete Builder siguen la interfaz de Builder y proporcionan 
   implementaciones específicas de los pasos de construcción. Su programa puede
   tener varias variaciones de Builders, implementadas de forma diferente. 
*/

class ConcreteBuilder1 implements Builder
{
  private $product;

  /* Una nueva instancia del constructor debe contener un objeto de producto en 
     blanco, que se utiliza en el ensamblaje posterior. 
  */


  public function __construct()
  {
    $this->reset();
  }

  public function reset(): void
  {
    $this->product = new Product1();
  }

  /* Todos los pasos de producción funcionan con la misma instancia de producto. */
  public function producePartA(): void
  {
    $this->product->parts[] = "PartA1";
  }

  public function producePartB(): void
  {
    $this->product->parts[] = "PartB1";
  }

  public function producePartC(): void
  {
    $this->product->parts[] = "PartC1";
  }

  /* Se supone que los constructores concretos proporcionan sus propios métodos para
     obtener resultados. Esto se debe a que diferentes tipos de constructores pueden 
     crear productos completamente diferentes que no siguen la misma interfaz.
     Por lo tanto, dichos métodos no pueden declararse en la interfaz base del constructor
     (al menos en un lenguaje de programación de tipado estático). Tenga en cuenta que PHP
     es un lenguaje de tipado dinámico y este método SÍ puede estar en la interfaz base.
     Sin embargo, no lo declararemos allí para mayor claridad. Normalmente, después de de-
     volver el resultado final al cliente, se espera que una instancia del constructor
     esté lista para comenzar a producir otro producto. Por eso es una práctica habitual 
     llamar al método de restablecimiento al final del cuerpo del método `getProduct`. Sin 
     embargo, este comportamiento no es obligatorio y puede hacer que sus constructores 
     esperen una llamada de restablecimiento explícita desde el código del cliente antes 
     de desechar el resultado anterior. 
  */

  public function getProduct(): Product1
  {
    $result = $this->product;
    $this->reset();

    return $result;
  }
}

/* Tiene sentido usar el patrón Constructor solo cuando sus productos son bastante complejos 
   y requieren una configuración extensa. A diferencia de otros patrones de creación, dife-
   rentes constructores concretos pueden producir productos no relacionados. En otras palabras,
   los resultados de varios constructores pueden no seguir siempre la misma interfaz. 
*/

class Product1
{
  public $parts = [];

  public function listParts(): void
  {
    debuguear("Product parts: " . implode(', ', $this->parts) . "\n\n");
  }
}


/* El Director solo es responsable de ejecutar los pasos de construcción en una secuencia espe-
   cífica. Resulta útil al producir productos según un orden o configuración específicos. En 
   sentido estricto, la clase Director es opcional, ya que el cliente puede controlar a los 
   constructores directamente. 
*/



class Director
{

  private $builder;

  /*
   El Director trabaja con cualquier instancia de Builder que el código del cliente le pase. 
   De esta manera, el código del cliente puede modificar el tipo final del producto recién 
   ensamblado.
  */
  public function setBuilder(Builder $builder): void
  {
    $this->builder = $builder;
  }

  /*
    El Director puede construir varias variaciones de producto utilizando los mismos pasos de construcción.
  */
  public function buildMinimalViableProduct(): void
  {
    $this->builder->producePartA();
  }

  public function buildFullFeaturedProduct(): void
  {
    $this->builder->producePartA();
    $this->builder->producePartB();
    $this->builder->producePartC();
  }
}



/* El código del cliente crea un objeto constructor, lo pasa al director y luego inicia el 
   proceso de construcción. El resultado final se obtiene del objeto constructor. 
*/
function clientCode(Director $director)
{
  $builder = new ConcreteBuilder1();
  $director->setBuilder($builder);

  debuguear("Standard basic product:\n");
  $director->buildMinimalViableProduct();
  $builder->getProduct()->listParts();

  debuguear("Standard full featured product:\n");
  $director->buildFullFeaturedProduct();
  $builder->getProduct()->listParts();

  // Recuerde que el patrón Builder se puede utilizar sin una clase Director.
  debuguear("Custom product:\n");
  $builder->producePartA();
  $builder->producePartC();
  $builder->getProduct()->listParts();
}
$director = new Director();
clientCode($director);
