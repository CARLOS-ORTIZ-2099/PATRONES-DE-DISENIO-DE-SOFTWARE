<?php


/* PHP tiene un par de interfaces integradas relacionadas con el patrón
   Observer.
   Así es como se ve la interfaz del Asunto:
   @link http://php.net/manual/en/class.splsubject.php
 
   interface SplSubject
   {
      // Adjuntar un observador al sujeto.
      public function attach(SplObserver $observer);
 
      // Separar al observador del sujeto.
      public function detach(SplObserver $observer);
 
      // Notificar a todos los observadores sobre un evento.
      public function notify();
   }
 
   También hay una interfaz incorporada para observadores:
   @link http://php.net/manual/en/class.splobserver.php
  
   interface SplObserver
   {
      public function update(SplSubject $subject);
   }

*/

/*
  El sujeto posee un estado importante y notifica a los observadores cuando 
  dicho estado cambia.
*/

class Subject implements \SplSubject
{
  /* Para simplificar, el estado del Sujeto, esencial para todos los 
     suscriptores, se almacena en esta variable.
  */
  public $state;

  /* Lista de suscriptores. En la vida real, la lista de suscriptores se 
     puede almacenar de forma más completa (clasificada por tipo de evento, 
     etc.).
  */

  private $observers;

  public function __construct()
  {
    $this->observers = new \SplObjectStorage();
  }

  // Los métodos de gestión de suscripciones.
  public function attach(\SplObserver $observer): void
  {
    debuguear("Subject: Attached an observer.");
    $this->observers->attach($observer);
  }

  public function detach(\SplObserver $observer): void
  {
    $this->observers->detach($observer);
    debuguear("Subject: Detached an observer.");
  }

  // Activar una actualización en cada suscriptor.
  public function notify(): void
  {
    debuguear("Subject: Notifying observers...");
    foreach ($this->observers as $observer) {
      $observer->update($this);
    }
  }

  /* Normalmente, la lógica de suscripción es solo una fracción de lo que un
     sujeto puede realmente hacer. Los sujetos suelen contener una lógica de 
     negocio importante que activa un método de notificación cuando algo 
     importante está a punto de suceder (o después).  
  */
  public function someBusinessLogic(): void
  {
    debuguear("Subject: I'm doing something important.");
    $this->state = rand(0, 10);

    debuguear("Subject: My state has just changed to: {$this->state}");
    $this->notify();
  }
}



/* Los Observadores Concretos reaccionan a las actualizaciones emitidas por 
   el Sujeto al que estaban asignados.
*/
class ConcreteObserverA implements \SplObserver
{
  public function update(\SplSubject $subject): void
  {
    if ($subject->state < 3) {
      debuguear("ConcreteObserverA: Reacted to the event.");
    }
  }
}

class ConcreteObserverB implements \SplObserver
{
  public function update(\SplSubject $subject): void
  {
    if ($subject->state == 0 || $subject->state >= 2) {
      debuguear("ConcreteObserverB: Reacted to the event.");
    }
  }
}

// El código de cliente.

$subject = new Subject();


$o1 = new ConcreteObserverA();
$subject->attach($o1);

$o2 = new ConcreteObserverB();
$subject->attach($o2);

$subject->someBusinessLogic();
$subject->someBusinessLogic();

$subject->detach($o2);

$subject->someBusinessLogic();
