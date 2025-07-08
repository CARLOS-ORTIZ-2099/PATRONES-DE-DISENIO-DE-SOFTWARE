<?php

/* La interfaz Subject declara operaciones comunes tanto para RealSubject
   como para el proxy. Mientras el cliente trabaje con RealSubject usando 
   esta interfaz, podrá pasarle un proxy en lugar de un sujeto real.
*/
interface Subject
{
  public function request(): void;
}


/* El RealSubject contiene cierta lógica de negocio básica. Normalmente, los 
   RealSubjects son capaces de realizar tareas útiles que también pueden ser 
   muy lentas o delicadas, por ejemplo, corregir datos de entrada. Un proxy 
   puede resolver estos problemas sin necesidad de modificar el código del 
   RealSubject.
*/
class RealSubject implements Subject
{
  public function request(): void
  {
    debuguear("RealSubject: Handling request.");
  }
}



// El Proxy tiene una interfaz idéntica al Sujeto Real.
class Proxy implements Subject
{

  private $realSubject;

  /* El proxy mantiene una referencia a un objeto de la clase Sujeto Real. 
     Puede cargarse de forma diferida o ser transferida al proxy por el 
     cliente.
  */
  public function __construct(RealSubject $realSubject)
  {
    $this->realSubject = $realSubject;
  }

  /* Las aplicaciones más comunes del patrón Proxy son la carga diferida, el 
     almacenamiento en caché, el control de acceso, el registro, etc. Un 
     Proxy puede realizar una de estas tareas y, según el resultado, pasar la 
     ejecución al mismo método en un objeto RealSubject vinculado.
  */
  public function request(): void
  {
    if ($this->checkAccess()) {
      $this->realSubject->request();
      $this->logAccess();
    }
  }

  private function checkAccess(): bool
  {
    // Aquí deberían hacerse algunos controles reales.
    debuguear("Proxy: Checking access prior to firing a real request.");

    return true;
  }

  private function logAccess(): void
  {
    debuguear("Proxy: Logging the time of request.");
  }
}




/* Se supone que el código del cliente funciona con todos los objetos (tanto 
   sujetos como proxies) a través de la interfaz Subject para ser compatible 
   tanto con sujetos reales como con proxies. Sin embargo, en la práctica, 
   los clientes suelen trabajar directamente con sus sujetos reales. En este 
   caso, para implementar el patrón con mayor facilidad, se puede extender el 
   proxy desde la clase del sujeto real.
*/
function clientCode(Subject $subject)
{
  $subject->request();
}



debuguear("Cliente: Ejecutar el código del cliente con un sujeto real:");
$realSubject = new RealSubject();
clientCode($realSubject);


debuguear("Cliente: Ejecutar el mismo código de cliente con un proxy:");
$proxy = new Proxy($realSubject);
clientCode($proxy);
