<?php

/* realizar un ejercicios que implemente el patron de compotamiento
   observer, para notificar cambios de un objeto a otros objetos 
   suscriptores, por ejemplo mandar un correo electronico a todos los 
   usuarios de la plataforma cuando se aproxime una rebaja de precios
   en una tienda , pero sólo a aquellos que se hayan suscripto a nuestro newLester.
*/




/* esta clase se encarga de gestionar las notificaciones a los objetos,
   ademas de tener lógica de negocio que trabaje en conjunto con dichas 
   notificaciones. 
*/
class Notify implements \SplSubject
{
  public $message;
  protected $observers;

  public function __construct()
  {
    // [0 => [ "obj" => {}, "info" => null ], 1=> [], 2=>[] ]
    $this->observers = new SplObjectStorage;
  }
  // este método se encargara de suscribir objetos para ser notificados
  public function attach(SplObserver $observer): void
  {
    $this->observers->attach($observer);
  }


  // este método se encargara de dessuscribir objetos de las notificaciones
  public function detach(SplObserver $observer): void
  {
    $this->observers->detach($observer);
  }

  // este método se encargara de notificar a los objetos suscritos
  public function notify($user_register = null): void
  {
    foreach ($this->observers as $observer) {
      $observer->update($this, $user_register);
    }
  }

  public function createMessage($message, $user)
  {
    $this->message = $message;
    $this->notify($user);
  }
}




class User
{
  public $name;
  public $email;


  public function __construct($name, $email)
  {
    $this->name = $name;
    $this->email = $email;
  }
}



class Logger implements \SplObserver
{
  public function update(\SplSubject $subject, $data = null): void
  {
    $rout = __DIR__ . "/records/" . $data->email . ".txt";

    $message = "Hola {$data->name} queremos decirte que : {$subject->message} . fecha :  " . date("Y-m-d H:i:s");

    if (!file_exists($rout)) {
      $file = fopen($rout, "x");
    }
    $file = fopen($rout, "w");
    fwrite($file, $message);
    fclose($file);
  }
}

$user1 = new User("juan", "juan1@gmail.com");
$user2 = new User("maria", "maria1@gmail.com");
debuguear($user1);

$notify = new Notify();
$logger = new Logger;

$notify->attach($logger);

debuguear($notify);
$notify->createMessage("rebajas de verano aprovechalas", $user1);
$notify->createMessage("rebajas de verano aprovechalas", $user2);
