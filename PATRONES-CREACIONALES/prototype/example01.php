<?php


/* Este patrón sirve para clonar objetos sin la necesidad de depender de sus clase 
   concretas, sólo de abstracciones.
   Nos sirve para crear objetos a partir de otros objetos(prototipos) ya existentes,
   es útil cuando la creación de dichos objetos es compleja y/o costosa esto nos ahorra
   tener que crearlo desde cero.
   
   Siempre que necesitemos instanciar un objeto cuyos datos han sido obtenidos previamente
   podemos recurrir a este patrón para evitar el proceso de instanciado realizando una 
   copia de un objeto existente.

   recalcar que hay 2 tipos de clonación 

   Clonación superficial : 
   sólo clona los datos que no sean objetos anidados, si hay propiedades con objetos estas
   se pasan por referencia, es mas eficiente en terminos de memoria pero puede tener efectos
   secundarios ya que varios objetos pueden apuntar al mismo lugar en memoria
    

   Clonación profunda : 
   esta hace una copia recursiva de todos los objetos anidados que puedan haber, es mas constosa
   hacer esta clonación pero nos da mayor seguridad ya que cada objeto es independiente del otro 


   Ventajas: Evita costosos procesos de creación, permite clonar estructuras complejas fácilmente,
   desacopla la lógica de instanciación.

   Desventajas: Puede aumentar el consumo de memoria si no se maneja correctamente la clonación 
   profunda, y puede ser innecesario si el objeto es simple de crear.
   
*/




/* La clase de ejemplo con capacidad de clonación. Veremos cómo se clonarán 
   los valores del campo con diferentes tipos. 
*/
class Prototype
{
  public $primitive;
  public $component;
  public $circularReference;
  /* PHP cuenta con soporte de clonación integrado. Puedes clonar un objeto sin 
     definir ningún método especial, siempre que tenga campos de tipos primitivos.
     Los campos que contienen objetos conservan sus referencias en un objeto clonado.
     Por lo tanto, en algunos casos, podrías querer clonar también esos objetos
     referenciados. Puedes hacerlo con el método especial `__clone()`.
  */
  public function __clone()
  {
    $this->component = clone $this->component;

    /* Clonar un objeto que tiene un objeto anidado con retrorreferencia requiere un 
       tratamiento especial. Una vez completada la clonación, el objeto anidado debe 
       apuntar al objeto clonado, en lugar del objeto original. 
    */
    $this->circularReference = clone $this->circularReference;
    $this->circularReference->prototype = $this;
  }
}

class ComponentWithBackReference
{
  public $prototype;

  /* Tenga en cuenta que el constructor no se ejecutará durante la clonación. Si tiene 
     lógica compleja dentro del constructor, es posible que deba ejecutarla también en 
     el método `__clone`.
  */
  public function __construct(Prototype $prototype)
  {
    $this->prototype = $prototype;
  }
}


// El código del cliente
function clientCode()
{
  $p1 = new Prototype();
  $p1->primitive = 245;
  $p1->component = new \DateTime();
  $p1->circularReference = new ComponentWithBackReference($p1);

  debuguear($p1);

  // todas las propiedades de $p1 se la pasamos, por valor exepto las intancias de clases 
  // esas se pasan por referencia, con clone lo clonamos y pasamos por valor
  $p2 = clone $p1;

  debuguear($p2);


  if ($p1->primitive === $p2->primitive) {
    debuguear("Los valores de campo primitivos se han transferido a un clon. ¡Genial!");
  } else {
    debuguear("Los valores de los campos primitivos no se han copiado. ¡Buuu!");
  }


  if ($p1->component === $p2->component) {
    debuguear("El componente simple no ha sido clonado. ¡Buuu!");
  } else {
    debuguear("Se ha clonado un componente simple. ¡Genial!");
  }


  if ($p1->circularReference === $p2->circularReference) {
    debuguear("El componente con referencia anterior no ha sido clonado. ¡Buuu!");
  } else {
    debuguear("Se ha clonado el componente con referencia inversa. ¡Genial!");
  }

  if ($p1->circularReference->prototype === $p2->circularReference->prototype) {
    debuguear("El componente con referencia inversa está vinculado al objeto original. ¡Buuu!");
  } else {
    debuguear("El componente con referencia inversa está vinculado al clon. ¡Genial!");
  }
}

clientCode();
