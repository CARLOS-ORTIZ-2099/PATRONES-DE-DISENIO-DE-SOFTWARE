<?php

/* Ejercicio: Sistema de Bebidas Personalizables
   Imagina que estás desarrollando un sistema para una cafetería que permite
   a los clientes personalizar sus bebidas. Las bebidas pueden ser básicas, 
   como café o té, y se les pueden agregar complementos como leche, azúcar, 
   crema, etc.

   Tu tarea es implementar este sistema utilizando el patrón Decorator.

   🎯 Requisitos:
   - Crea una interfaz base llamada Bebida con un método costo(): number y otro método descripcion(): string.

   - Implementa una clase concreta llamada BebidaBase que represente una bebida básica (por ejemplo, café o té).

   - Crea decoradores que añadan complementos a las bebidas:

    - Leche: Añade un costo adicional y actualiza la descripción.

    - Azúcar: Añade un costo adicional y actualiza la descripción.

    - Crema: Añade un costo adicional y actualiza la descripción.

   - Permite combinar decoradores para que una bebida pueda tener múltiples complementos.

*/

//interface que define la estructura base de los envoltorios y objetos a envolver
interface Bebida
{
  public function costo(): int|float;

  public function descripcion(): string;
}
// componentes concretos
// representan los objetos que se decoraran
class BebidaBase implements Bebida
{

  public string $name;
  public int $price;
  public function __construct($name, $price)
  {
    $this->name = $name;
    $this->price = $price;
  }

  public function costo(): int|float
  {
    return $this->price;
  }

  public function descripcion(): string
  {
    return "este es una bebida del tipo: " . $this->name . "<br/>";
  }
}

class SuperBebida implements Bebida
{
  public string $name;
  public int $price;
  public function __construct($name, $price)
  {
    $this->name = $name;
    $this->price = $price;
  }

  public function costo(): int|float
  {
    return $this->price;
  }

  public function descripcion(): string
  {
    return "este es una bebida del tipo: " . $this->name . "<br/>";
  }
}

// decorador(wrapper), define la estructura base de un decorador
// este es un "componente" y tiene un "componente"
// por lo tanto un decorador puede contener un componente concreto y/o
// otro decorador
abstract class BebidaDecorator implements Bebida
{
  public Bebida $bebida;
  public function __construct(Bebida $bebida)
  {
    $this->bebida = $bebida;
  }

  public function costo(): int|float
  {
    return $this->bebida->costo();
  }

  public function descripcion(): string
  {
    return $this->bebida->descripcion();
  }
}

// representa un decorador concreto que heredan de BebidaDecorator
// estos se encargaran de añadir y/o sobreescribir comportamientos
class BebidaDecoratorLeche extends BebidaDecorator
{
  public function costo(): int|float
  {
    $costoBase = parent::costo(); // 53.1 + 10 = 63.1
    $nuevoCosto = $costoBase + 10;
    return $nuevoCosto;
  }
  public function descripcion(): string
  {
    $descripcionBase = parent::descripcion();
    $nuevaDescripcion = $descripcionBase . " ademas se le agrego leche con un costo de " . $this->costo() . "<br/>";
    return $nuevaDescripcion;
  }
}

class BebidaDecoratorWhitCream extends BebidaDecorator
{

  public function costo(): int|float
  {
    $costoBase = parent::costo(); //63.1+ 100 = 163.1
    $nuevoCosto = $costoBase + 100;
    return $nuevoCosto;
  }
  public function descripcion(): string
  {
    $descripcionBase = parent::descripcion();
    $nuevaDescripcion = $descripcionBase . " ademas se le agrego crema con un costo de " . $this->costo() . "<br/>";
    return $nuevaDescripcion;
  }
}



$bebidaBase = new BebidaBase("te", 15);
debuguear($bebidaBase);
$superbebida = new SuperBebida("red bull", 50);
debuguear($superbebida);


// decorador basico
$bebidaDecoratorLeche = new BebidaDecoratorLeche($superbebida);
debuguear($bebidaDecoratorLeche);
debuguear($bebidaDecoratorLeche->costo());
debuguear($bebidaDecoratorLeche->descripcion());


// decorador basico
$bebidaDecoratorCrema = new BebidaDecoratorWhitCream($bebidaBase);
debuguear($bebidaDecoratorCrema);
debuguear($bebidaDecoratorCrema->costo());
debuguear($bebidaDecoratorCrema->descripcion());



// decorador compuesto 
$bebidaCustom = new BebidaDecoratorLeche(new BebidaDecoratorWhitCream($bebidaBase));
debuguear($bebidaCustom);
debuguear($bebidaCustom->costo());
debuguear($bebidaCustom->descripcion());


/* Ejercicio: Sistema de Facturación de Bebidas 
   Imagina que ahora estás desarrollando un sistema de facturación para la 
   cafetería. Además de los complementos como leche y crema, el sistema debe 
   calcular el precio final considerando impuestos y descuentos.

   Tu tarea es extender el sistema anterior para incluir estas funcionalidades 
   adicionales utilizando el patrón Decorator.

   🎯 Requisitos:

   Impuestos:

    - Crea un decorador llamado ImpuestoDecorator que añada un porcentaje de 
    impuesto al costo total de la bebida.

    - El porcentaje de impuesto debe ser configurable (por ejemplo, 18%).

   Descuentos:

    - Crea un decorador llamado DescuentoDecorator que aplique un descuento fijo 
    al costo total de la bebida.

    - El monto del descuento debe ser configurable (por ejemplo, \5 \ unidades 
    monetarias).

   Encadenamiento:

    - Permite combinar decoradores para que una bebida pueda tener complementos, 
    impuestos y descuentos al mismo tiempo.

   Salida detallada:

    - La descripción de la bebida debe incluir todos los detalles, como los complementos añadidos, el impuesto aplicado y el descuento.

*/

abstract class AjustesDecorator implements Bebida
{
  public Bebida $bebida;
  public function __construct(Bebida $bebida)
  {
    $this->bebida = $bebida;
  }

  public function costo(): int|float
  {
    return $this->bebida->costo();
  }

  public function descripcion(): string
  {
    return $this->bebida->descripcion();
  }
}

class ImpuestoDecorator extends AjustesDecorator
{
  public function impuesto()
  {
    return 18;
  }

  public function costo(): int|float
  {
    $costoActual = parent::costo(); // 45 + 8.1 = 53.1
    $costoConInpuesto = $costoActual + ($costoActual * $this->impuesto()) / 100;
    return $costoConInpuesto;
  }

  public function costoAntesImpuesto()
  {
    $costoActual = parent::costo();
    return $costoActual;
  }

  public function descripcion(): string
  {
    $descripcionBase = parent::descripcion();
    $nuevaDescripcion = $descripcionBase . " y tiene un impuesto de " . $this->impuesto() . "% y un costo de " . $this->costoAntesImpuesto() . " y al aplicarle impuesto se subio a " . $this->costo() . "<br/>";
    return $nuevaDescripcion;
  }
}


class DescuentoDecorator extends AjustesDecorator
{
  public function descuento()
  {
    return 10;
  }

  public function costo(): int|float
  {
    $costoActual = parent::costo(); //50 - 5 = 45
    $costoConDescuento = $costoActual - ($costoActual * $this->descuento()) / 100;
    return $costoConDescuento;
  }

  public function costoAntesDelDescuento()
  {
    $costoActual = parent::costo(); //50 - 5 = 45
    return $costoActual;
  }

  public function descripcion(): string
  {
    $descripcionBase = parent::descripcion();
    $nuevaDescripcion =  $descripcionBase . " y tiene un descuento de " . $this->descuento()
      . "% y un costo de " . $this->costoAntesDelDescuento()
      . " y al aplicarle descuento se rebajo a :" . $this->costo() . "<br/>";
    return $nuevaDescripcion;
  }
}

debuguear("sección de ajustes de pago");

$newInpuestoDecorator = new ImpuestoDecorator($superbebida);
debuguear($newInpuestoDecorator);
debuguear($newInpuestoDecorator->costo());
debuguear($newInpuestoDecorator->descripcion());

$newDescuentoDecorator = new DescuentoDecorator($superbebida);
debuguear($newDescuentoDecorator);
debuguear($newDescuentoDecorator->costo());
debuguear($newDescuentoDecorator->descripcion());

debuguear("seccion de pruebas");


/* esto es composición de clases, mejor que la herencia ya que no esto 
   "amarrado"a una implementación concreta ya que de esta manera puedo montar y desmontar decoraciones de manera dinamica.
   Recalcar tambien que el orden en como compongamos nuestras decoraciones, altera el resultado final, por eso es muy importante definir bien el orden de composición. 

*/

$objeto1 = new DescuentoDecorator(new ImpuestoDecorator(new BebidaDecoratorLeche(new BebidaDecoratorWhitCream($superbebida))));
debuguear($objeto1);
debuguear($objeto1->costo());
debuguear($objeto1->descripcion());



$objeto2 = new BebidaDecoratorWhitCream(new BebidaDecoratorLeche(new ImpuestoDecorator(new DescuentoDecorator($superbebida))));
debuguear($objeto2);
debuguear($objeto2->costo());
debuguear($objeto2->descripcion());


// montando decoradores dinamicamente

$montarDesmontar = function ($number) use ($bebidaBase, $superbebida) {

  $objeto3 = null;
  if ($number % 2 == 0) {
    // instanciar objetos con sólo 2 decoradores
    $objeto3 =  new BebidaDecoratorLeche(new BebidaDecoratorWhitCream($bebidaBase));
  } else {
    // instancira objetos con todos los decoradores
    $objeto3 = new BebidaDecoratorWhitCream(new BebidaDecoratorLeche(new ImpuestoDecorator(new DescuentoDecorator($superbebida))));
  }
  debuguear($objeto3);
};

$montarDesmontar(7);
