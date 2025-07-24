<?php

/* El contexto define la interfaz de interés para los clientes. También 
   mantiene una referencia a una instancia de una subclase de estado, que 
   representa el estado actual del contexto.
*/
class Context
{
  // Una referencia al estado actual del Contexto.
  private $state;

  public function __construct(State $state)
  {
    $this->transitionTo($state);
  }

  // El Contexto permite cambiar el objeto Estado en tiempo de ejecución.
  public function transitionTo(State $state): void
  {
    debuguear("Context: Transition to " . get_class($state));
    $this->state = $state;
    $this->state->setContext($this);
  }

  // El Contexto delega parte de su comportamiento al objeto Estado actual.
  public function request1(): void
  {
    $this->state->handle1();
  }

  public function request2(): void
  {
    $this->state->handle2();
  }
}

/* La clase base Estado declara métodos que todos los Estados Concretos 
   deben implementar y también proporciona una retrorreferencia al objeto 
   Contexto, asociado con el Estado. Esta retrorreferencia puede ser 
   utilizada por los Estados para la transición del Contexto a otro Estado.
*/
abstract class State
{

  protected Context $context;

  public function setContext(Context $context)
  {
    $this->context = $context;
  }

  abstract public function handle1(): void;

  abstract public function handle2(): void;
}

/* Los Estados Concretos implementan diversos comportamientos, asociados con 
   un estado del Contexto.
*/
class ConcreteStateA extends State
{
  public function handle1(): void
  {
    debuguear("ConcreteStateA handles request1.");
    debuguear("ConcreteStateA wants to change the state of the context.");
    $this->context->transitionTo(new ConcreteStateB());
  }

  public function handle2(): void
  {
    debuguear("ConcreteStateA handles request2.");
  }
}

class ConcreteStateB extends State
{
  public function handle1(): void
  {
    debuguear("ConcreteStateB handles request1.");
  }

  public function handle2(): void
  {
    debuguear("ConcreteStateB handles request2.");
    debuguear("ConcreteStateB wants to change the state of the context.");
    $this->context->transitionTo(new ConcreteStateA());
  }
}

/// El código de cliente.
// inicialmente tiene un estado A
$context = new Context(new ConcreteStateA());
//luego hacemos el camhio a el estado B
$context->request1();
// luego volvemos a cambiarlo a A
$context->request2();
