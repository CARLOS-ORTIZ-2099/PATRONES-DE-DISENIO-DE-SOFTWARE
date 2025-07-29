<?php

// La clase abstracta define el método de plantilla y declara todos sus pasos.
abstract class SocialNetwork
{
  protected $username;

  protected $password;

  public function __construct(string $username, string $password)
  {
    $this->username = $username;
    $this->password = $password;
  }

  /* El método de plantilla invoca pasos abstractos en un orden específico. 
     Una subclase puede implementar todos los pasos, lo que permite que este 
     método publique algo en una red social.
  */
  public function post(string $message): bool
  {
    /* Autentícate antes de publicar. Cada red utiliza un método de 
       autenticación diferente.  
    */
    if ($this->logIn($this->username, $this->password)) {
      // Envía los datos de la publicación. Cada red tiene API diferentes.
      $result = $this->sendData($message);
      $this->verpublicaciones();
      $this->logOut();
      return $result;
    }

    return false;
  }

  /* Los pasos se declaran abstractos para obligar a las subclases a 
     implementarlos todos.
  */
  abstract public function logIn(string $userName, string $password): bool;

  abstract public function sendData(string $message): bool;

  abstract public function logOut(): void;

  protected function verpublicaciones()
  {
    debuguear("esta viendo las publicaciones");
  }
}

// Esta clase concreta implementa la API de Facebook (bueno, eso pretende).
class Facebook extends SocialNetwork
{
  public function logIn(string $userName, string $password): bool
  {
    debuguear("\nChecking user's credentials...");
    debuguear("Name: " . $this->username);
    debuguear("Password: " . str_repeat("*", strlen($this->password)));

    simulateNetworkLatency();

    debuguear("Facebook: '" . $this->username . "' has logged in successfully.");

    return true;
  }

  public function sendData(string $message): bool
  {
    debuguear("Facebook: '" . $this->username . "' has posted '" . $message . "'.");

    return true;
  }

  public function logOut(): void
  {
    debuguear("Facebook: '" . $this->username . "' has been logged out.");
  }
}

// Esta clase concreta implementa la API de Twitter.
class Twitter extends SocialNetwork
{
  public function logIn(string $userName, string $password): bool
  {
    debuguear("Checking user's credentials...");
    debuguear("Name: " . $this->username);
    debuguear("Password: " . str_repeat("*", strlen($this->password)));

    simulateNetworkLatency();

    debuguear("Twitter: '" . $this->username . "' has logged in successfully.");

    return true;
  }

  public function sendData(string $message): bool
  {
    debuguear("Twitter: '" . $this->username . "' has posted '" . $message . "'");

    return true;
  }

  public function logOut(): void
  {
    debuguear("Twitter: '" . $this->username . "' has been logged out.");
  }
}

// Una pequeña función de ayuda que hace que los tiempos de espera parezcan reales.
function simulateNetworkLatency()
{
  $i = 0;
  while ($i < 1) {
    echo ".";
    sleep(1);
    $i++;
  }
}

// El código de cliente.

$username = readline("Username: \n");
$password = readline("Password: \n");
$message = readline("Message: \n");

$choice = readline(
  "Choose the social network to post the message:\n" .
    "1 - Facebook\n" .
    "2 - Twitter\n"
);

// Now, let's create a proper social network object and send the message.
if ($choice == 1) {
  $network = new Facebook($username, $password);
} elseif ($choice == 2) {
  $network = new Twitter($username, $password);
} else {
  die("Sorry, I'm not sure what you mean by that.\n");
}
$network->post($message);
