<?php

/* El patrón Facade proporciona una interfaz simple y unificada para
   interactuar con un sistema o conjunto de clases complejas. Es 
   especialmente útil cuando trabajamos con bibliotecas externas, 
   frameworks o múltiples componentes internos que pueden resultar engo-
   rrosos de manejar directamente.
   La idea es esconder la complejidad del sistema interno detrás de una 
   "fachada" que expone solo los métodos necesarios, facilitando así el 
   uso del sistema sin que el cliente tenga que conocer todos sus detalles.

   por ejemplo un sistema de ventas que, al generar una compra, debe:

   - Procesar el pago con una entidad financiera
   - Actualizar el inventario
   - Emitir una boleta o comprobante
   - Coordinar el envío si aplica
   - Notificar al cliente vía email o mensaje

   Cada una de estas tareas puede estar manejada por clases independientes 
   (Payments, Inventory, Delivery, Notification), y orquestarlas 
   directamente desde el cliente puede ser tedioso o propenso a errores.

   Con el patrón Facade, podríamos tener una clase VentaFacade que exponga 
   un único método como realizarCompra(), y esta se encargaría de coordinar 
   todos los pasos internamente. Así el cliente trabaja con una sola puerta 
   de entrada “la fachada” sin preocuparse de los detalles internos.

   En resumen: Facade no elimina la complejidad, pero sí la esconde 
   elegantemente para que el cliente no tenga que lidiar con ella directamente. 
   Ideal para mantener el código modular, comprensible y fácil de mantener.  
*/


/* La clase Facade proporciona una interfaz sencilla para la lógica compleja
   de uno o varios subsistemas. Facade delega las solicitudes del cliente a 
   los objetos correspondientes dentro del subsistema. Facade también es 
   responsable de gestionar su ciclo de vida. Todo esto protege al cliente de 
   la complejidad no deseada del subsistema.
*/

class Facade
{
  protected $subsystem1;

  protected $subsystem2;

  /* Dependiendo de las necesidades de su aplicación, puede proporcionar a la 
     Fachada objetos de subsistema existentes o forzar a la Fachada a 
     crearlos por sí misma.
  */
  public function __construct(
    Subsystem1|null $subsystem1 = null,
    Subsystem2|null $subsystem2 = null
  ) {
    $this->subsystem1 = $subsystem1 ?: new Subsystem1();
    $this->subsystem2 = $subsystem2 ?: new Subsystem2();
  }

  /* Los métodos de Facade son atajos prácticos para acceder a la sofisticada 
     funcionalidad de los subsistemas. Sin embargo, los clientes solo acceden 
     a una fracción de sus capacidades.
  */
  public function operation(): string
  {
    $result = "Facade inicializa subsistemas:\n";
    $result .= $this->subsystem1->operation1();
    $result .= $this->subsystem2->operation1();
    $result .= "La fachada ordena a los subsistemas que realicen la acción:\n";
    $result .= $this->subsystem1->operationN();
    $result .= $this->subsystem2->operationZ();

    return $result;
  }
}

/* El Subsistema puede aceptar solicitudes tanto de la fachada como del   
   cliente directamente.
   En cualquier caso, para el Subsistema, la Fachada es un cliente más, y no 
   forma parte del Subsistema.
*/

class Subsystem1
{
  public function operation1(): string
  {
    return "Subsystem1: Ready!\n";
  }

  public function operationN(): string
  {
    return "Subsystem1: Go!\n";
  }
}

/* Algunas fachadas pueden funcionar con múltiples subsistemas al mismo 
   tiempo.
*/
class Subsystem2
{
  public function operation1(): string
  {
    return "Subsystem2: Get ready!\n";
  }

  public function operationZ(): string
  {
    return "Subsystem2: Fire!\n";
  }
}

/* El código del cliente trabaja con subsistemas complejos a través de una 
   interfaz sencilla
   proporcionada por la Fachada. Cuando una fachada gestiona el ciclo de vida 
   del subsistema, el cliente podría incluso desconocer su existencia. Este
   enfoque permite mantener la complejidad bajo control.
*/
function clientCode(Facade $facade)
{
  debuguear($facade->operation());
}

/* El código del cliente podría tener algunos objetos del subsistema ya 
   creados. En este caso, podría ser útil inicializar la Fachada con estos 
   objetos en lugar de dejar que la Fachada cree nuevas instancias.
*/
$subsystem1 = new Subsystem1();
$subsystem2 = new Subsystem2();
$facade = new Facade($subsystem1, $subsystem2);
clientCode($facade);
