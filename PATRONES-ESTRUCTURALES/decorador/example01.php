<?php

/* La interfaz base del componente define operaciones que pueden ser
   modificadas por decoradores.
*/
interface Component
{
  public function operation(): string;
}

/* Los componentes concretos proporcionan implementaciones predeterminadas
   de las operaciones. Puede haber varias variaciones de estas clases.
*/
class ConcreteComponent implements Component
{
  public function operation(): string
  {
    return "ConcreteComponent";
  }
}

/* La clase base Decorator sigue la misma interfaz que los demás componentes.
   El propósito principal de esta clase es definir la interfaz de envoltura
   para todos los decoradores concretos. La implementación predeterminada
   del código de envoltura podría
   incluir un campo para almacenar un componente envuelto y los medios para 
   inicializarlo.
*/
class Decorator implements Component
{

  protected $component;

  public function __construct(Component $component)
  {
    $this->component = $component;
  }

  // El Decorador delega todo el trabajo al componente envuelto..

  public function operation(): string
  {
    return $this->component->operation();
  }
}

/* Los decoradores concretos llaman al objeto envuelto y alteran su   
   resultado de alguna manera.
*/
class ConcreteDecoratorA extends Decorator
{
  /* Los decoradores pueden llamar a la implementación principal de la 
     operación, en lugar de llamar directamente al objeto encapsulado. Este 
     enfoque simplifica la extensión de las clases decoradoras.
  */
  public function operation(): string
  {
    return "ConcreteDecoratorA(" . parent::operation() . ")";
  }
}

/* Los decoradores pueden ejecutar su comportamiento antes o después de la 
   llamada a un objeto envuelto.
*/
class ConcreteDecoratorB extends Decorator
{
  public function operation(): string
  {
    return "ConcreteDecoratorB(" . parent::operation() . ")";
  }
}

/* El código del cliente funciona con todos los objetos que utilizan la   
   interfaz Component. De esta forma, puede mantenerse independiente de las 
   clases concretas de los componentes con los que trabaja.
*/
function clientCode(Component $component)
{

  debuguear("RESULT: " . $component->operation());
}

/* De esta manera, el código del cliente puede soportar componentes 
   simples...
*/
$simple = new ConcreteComponent();
debuguear("Cliente: Tengo un componente simple:");
clientCode($simple);


/* ...así como los decorados.
   Observe cómo los decoradores pueden envolver no solo componentes simples, 
   sino también otros decoradores.
*/
$decorator1 = new ConcreteDecoratorA($simple);
clientCode($decorator1);

$decorator2 = new ConcreteDecoratorB($decorator1);
debuguear("Cliente: Ahora tengo un componente decorado:");
clientCode($decorator2);
