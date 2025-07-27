<?php

// La interfaz Command declara un método para ejecutar un comando.
interface Command
{
  public function execute(): void;
}

// Algunos comandos pueden implementar operaciones simples por sí solos.
class SimpleCommand implements Command
{
  private $payload;

  public function __construct(string $payload)
  {
    $this->payload = $payload;
  }

  public function execute(): void
  {
    debuguear("SimpleCommand: See, I can do simple things like printing (" . $this->payload . ")");
  }
}

/* Sin embargo, algunos comandos pueden delegar operaciones más complejas a 
   otros objetos, llamados "receptores".
*/
class ComplexCommand implements Command
{

  private Receiver $receiver;

  // Datos de contexto, necesarios para lanzar los métodos del receptor.
  private $a;

  private $b;

  /* Los comandos complejos pueden aceptar uno o varios objetos receptores 
     junto con cualquier dato de contexto a través del constructor.
  */
  public function __construct(Receiver $receiver, string $a, string $b)
  {
    $this->receiver = $receiver;
    $this->a = $a;
    $this->b = $b;
  }

  // Los comandos pueden delegar a cualquier método de un receptor.
  public function execute(): void
  {
    debuguear("ComplexCommand: Complex stuff should be done by a receiver object.");
    $this->receiver->doSomething($this->a);
    $this->receiver->doSomethingElse($this->b);
  }
}

/* Las clases receptoras contienen lógica de negocio importante. Saben cómo
   realizar todo tipo de operaciones asociadas con la ejecución de una 
   solicitud. De hecho, cualquier clase puede actuar como receptora.
*/
class Receiver
{
  public function doSomething(string $a): void
  {
    debuguear("Receiver: Working on (" . $a . ".)");
  }

  public function doSomethingElse(string $b): void
  {
    debuguear("Receiver: Also working on (" . $b . ".)");
  }
}

/* El invocador está asociado a uno o varios comandos. Envía una solicitud al 
   comando.
*/
class Invoker
{

  private Command $onStart;

  private Command $onFinish;

  // Inicializar comandos.
  public function setOnStart(Command $command): void
  {
    $this->onStart = $command;
  }

  public function setOnFinish(Command $command): void
  {
    $this->onFinish = $command;
  }

  /* El invocador no depende de comandos concretos ni de clases receptoras. 
     El invocador pasa una solicitud a un receptor indirectamente, ejecutando 
     un comando.
  */
  public function doSomethingImportant(): void
  {
    debuguear("Invoker: Does anybody want something done before I begin?");
    if ($this->onStart instanceof Command) {
      $this->onStart->execute();
    }

    debuguear("Invoker: ...doing something really important...");

    debuguear("Invoker: Does anybody want something done after I finish?");
    if ($this->onFinish instanceof Command) {
      $this->onFinish->execute();
    }
  }
}

// El código del cliente puede parametrizar un invocador con cualquier comando.
$invoker = new Invoker();
$invoker->setOnStart(new SimpleCommand("Say Hi!"));
$receiver = new Receiver();
$invoker->setOnFinish(new ComplexCommand($receiver, "Send email", "Save report"));

$invoker->doSomethingImportant();
/* sirve para definir métodos de comportamiento que a su vez ejecutaran 
   metodos de otras implementaciones(clases llamadas receptoras), que estan 
   encapsulados por la implementación del comando durante su creacion
*/