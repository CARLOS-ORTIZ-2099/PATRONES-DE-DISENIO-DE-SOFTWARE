<?php

/* El patrón Adapter es útil cuando queremos hacer que dos objetos con
   interfaces incompatibles trabajen juntos, como por ejemplo una laptop 
   con una toma de carga de 3 entradas pero el enchufe de pared solo tiene
   2 disponibles, entonces podemos usar un adaptador que "adapte" la entrada 
   de la laptop con la pared, se podria decir que es como el intermediario 
   entre lo que tengo con lo que se espera.

   En términos técnicos, ese "adaptador"  es una clase que:
   - Implementa la interfaz que el cliente espera

   - Internamente utiliza una instancia del componente que ya existe, pero 
     cuya interfaz es diferente

   - Se encarga de traducir las llamadas del cliente a la forma que entiende 
     el componente original

*/


/* El objetivo define la interfaz específica del dominio utilizada por el 
   código del cliente.
*/
class Target
{
  public function request(): string
  {
    return "Target: The default target's behavior.";
  }
}

/* El Adaptee(Adaptado) contiene algunos comportamientos útiles, pero su
   interfaz es incompatible con el código de cliente existente. El Adaptee 
   necesita cierta adaptación antes de que el código de cliente pueda usarlo.
*/
class Adaptee
{
  public function specificRequest(): string
  {
    return ".eetpadA eht fo roivaheb laicepS";
  }
}

/* El adaptador hace que la interfaz del adaptado sea compatible con la 
   interfaz del objetivo.
*/
class Adapter extends Target
{
  private $adaptee;

  public function __construct(Adaptee $adaptee)
  {
    $this->adaptee = $adaptee;
  }

  public function request(): string
  {
    return "Adapter: (TRANSLATED) " . strrev($this->adaptee->specificRequest());
  }
}

/* El código del cliente admite todas las clases que siguen la interfaz de 
   destino.
*/
function clientCode(Target $target)
{
  debuguear($target->request());
}

debuguear("Cliente: Puedo trabajar perfectamente con los objetos Target:");
$target = new Target();
clientCode($target);


$adaptee = new Adaptee();
debuguear("Cliente: La clase Adaptador tiene una interfaz extraña. Mira, no la entiendo.");
debuguear("Adaptee: " . $adaptee->specificRequest());


debuguear("Cliente: Pero puedo trabajar con él a través del Adaptador:");
$adapter = new Adapter($adaptee);
clientCode($adapter);
