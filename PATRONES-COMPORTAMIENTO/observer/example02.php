<?php

/* El Repositorio de Usuarios representa un Sujeto. Diversos objetos están 
   interesados en rastrear su estado interno, ya sea añadiendo o eliminando 
   un usuario.
*/
class UserRepository implements \SplSubject
{
  // La lista de usuarios.
  private $users = [];

  /* Aquí va la infraestructura de gestión del Observador. Tenga en cuenta que 
     no es todo de lo que es responsable nuestra clase. Su lógica de negocio 
     principal se detalla debajo de estos métodos.  
  */

  private $observers = [];
  // observers será un arreglo asociativo ["*" => []]
  public function __construct()
  {
    // Un grupo de eventos especial para observadores que desean escuchar todos los eventos.
    $this->observers["*"] = [];
  }
  // método que se encarga de corroborar si una clave existe en el arreglo de observers
  // si no existe la crea
  private function initEventGroup(string $event = "*"): void
  {
    if (!isset($this->observers[$event])) {
      $this->observers[$event] = [];
    }
  }

  // obtiene 2 arreglos y los retorna ya fusionados
  private function getEventObservers(string $event = "*"): array
  {
    $this->initEventGroup($event);
    $group = $this->observers[$event];
    $all = $this->observers["*"];
    $copy = array_merge($group, $all);
    //debuguear($copy);
    return $copy;
    // retorna algo como esto => [ {}, {}, {}, {} ]
  }

  // este método se encargara de registrar observadores según el tipo de evento
  public function attach(\SplObserver $observer, string $event = "*"): void
  {
    $this->initEventGroup($event);
    // en este punto $event ya existe como clave del arreglos asociativo observers
    // y este tiene como valor un arreglo por lo cual sólo le insertamos ese observador
    $this->observers[$event][] = $observer;
  }

  // método que se encargara de eliminar un objeto del arreglo de suscriptores
  public function detach(\SplObserver $observer, string $event = "*"): void
  {
    // recorremos el arreglo fucionado
    foreach ($this->getEventObservers($event) as $key => $s) {
      // comprobamos si el objeto iterado es igual al observador que se quiere eliminar
      if ($s === $observer) {
        unset($this->observers[$event][$key]);
      }
    }
  }

  // método que se encargara de notificar a todos los suscriptores
  public function notify(string $event = "*", $data = null): void
  {
    debuguear("UserRepository: Broadcasting the '$event' event.");
    foreach ($this->getEventObservers($event) as $observer) {
      $observer->update($this, $event, $data);
    }
  }

  // Aquí están los métodos que representan la lógica empresarial de la clase.

  public function initialize($filename): void
  {
    debuguear("UserRepository: Loading user records from a file.");
    $this->notify("users:init", $filename);
  }

  public function createUser(array $data): User
  {
    debuguear("UserRepository: Creating a user.");
    // creamos el usuario y actualizamos sus datos
    $user = new User();
    $user->update($data);

    $id = bin2hex(openssl_random_pseudo_bytes(16));
    $user->update(["id" => $id]);
    // luego registramos en el arreglo de usuarios de esta clase a dicho usuario
    $this->users[$id] = $user;
    // luego notificamos a todos los suscriptores según el tipo de evento
    $this->notify("users:created", $user);

    return $user;
  }

  public function updateUser(User $user, array $data): User|null
  {
    debuguear("UserRepository: Updating a user.");
    // obteniendo el id del usuario a actualizar
    $id = $user->attributes["id"];
    // si ese id no existe en el arreglo de usuarios retorna null
    if (!isset($this->users[$id])) {
      return null;
    }
    // si existe lo actualizamos y luego notificamos a todos los observadores
    $user = $this->users[$id];
    $user->update($data);

    $this->notify("users:updated", $user);

    return $user;
  }

  public function deleteUser(User $user): void
  {
    debuguear("UserRepository: Deleting a user.");
    // obteniendo el id del usuario a eliminar
    $id = $user->attributes["id"];
    // si ese id no existe en el arreglo de usuarios no hacemos nada
    if (!isset($this->users[$id])) {
      return;
    }
    // si existe lo eliminamos y luego notificamos a todos los observadores
    unset($this->users[$id]);

    $this->notify("users:deleted", $user);
  }
}

// Mantengamos la clase Usuario trivial ya que no es el foco de nuestro ejemplo.
class User
{
  public $attributes = [];

  public function update($data): void
  {
    $this->attributes = array_merge($this->attributes, $data);
  }
}

// Este componente concreto registra todos los eventos al que está suscrito.
class Logger implements \SplObserver
{
  private $filename;

  public function __construct($filename)
  {
    $this->filename = $filename;
    if (file_exists($this->filename)) {
      unlink($this->filename);
    }
  }

  // método que se encarga de registrar los eventos a los que esta suscrito
  public function update(\SplSubject $repository, string $event = null, $data = null): void
  {
    $entry = date("Y-m-d H:i:s") . ": '$event' with data '" . json_encode($data) . "'\n";
    file_put_contents($this->filename, $entry, FILE_APPEND);

    debuguear("Logger: I've written '$event' entry to the log.");
  }
}

/* Este componente concreto envía instrucciones iniciales a los nuevos 
   usuarios. El cliente es responsable de vincular este componente a un 
   evento de creación de usuario adecuado. 
*/
class OnboardingNotification implements \SplObserver
{
  private $adminEmail;

  public function __construct($adminEmail)
  {
    $this->adminEmail = $adminEmail;
  }

  public function update(\SplSubject $repository, string $event = null, $data = null): void
  {
    // mail($this->adminEmail,
    //     "Se requiere incorporación",
    //     "Tenemos un nuevo usuario. Aquí está su información.: " .json_encode($data));

    debuguear("OnboardingNotification: The notification has been emailed!");
  }
}

// El código de cliente.

$repository = new UserRepository();
//debuguear($repository, true);
$repository->attach(new Logger(__DIR__ . "/records/log.txt"), "*");

$repository->attach(new OnboardingNotification("1@example.com"), "users:created");

debuguear($repository);

$repository->initialize(__DIR__ . "/users.csv");

debuguear($repository);

$user = $repository->createUser([
  "name" => "John Smith",
  "email" => "john99@example.com",
]);
debuguear($repository);

$repository->deleteUser($user);
debuguear($repository);
