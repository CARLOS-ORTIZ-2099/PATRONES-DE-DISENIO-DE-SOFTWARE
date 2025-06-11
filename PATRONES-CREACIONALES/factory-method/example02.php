<?php


/*El Creador declara un método de fábrica que puede utilizarse como sustituto de las
  llamadas directas al constructor de productos, por ejemplo:
 
  - Before: $p = new FacebookConnector();
  - After: $p = $this->getSocialNetwork;
  Esto permite cambiar el tipo de producto que crean las subclases de SocialNetworkPosters.
  
*/

abstract class SocialNetworkPoster
{
  /*El método de fábrica real. Nótese que devuelve el conector abstracto. Esto permite que
    las subclases devuelvan cualquier conector concreto sin romper el contrato de la 
    superclase.
  */
  abstract public function getSocialNetwork(): SocialNetworkConnector;

  /*Cuando se utiliza el método de fábrica dentro de la lógica de negocios del Creador, las
    subclases pueden alterar la lógica indirectamente al devolver diferentes tipos de conector 
    desde el método de fábrica.
  */

  public function post($content): void
  {
    // Llame al método de fábrica para crear un objeto Producto...
    $network = $this->getSocialNetwork();
    // ...luego úsalo como quieras.
    $network->logIn();
    $network->createPost($content);
    $network->logout();
  }
}

/*Este Creador Concreto es compatible con Facebook. Recuerda que esta clase también hereda el
  método "post" de la clase padre. Los Creadores Concretos son las clases que el Cliente utiliza.
*/
class FacebookPoster extends SocialNetworkPoster
{
  private $login, $password;

  public function __construct(string $login, string $password)
  {
    $this->login = $login;
    $this->password = $password;
  }

  public function getSocialNetwork(): SocialNetworkConnector
  {
    return new FacebookConnector($this->login, $this->password);
  }
}

// Este creador de concreto es compatible con LinkedIn.

class LinkedInPoster extends SocialNetworkPoster
{
  private $email, $password;

  public function __construct(string $email, string $password)
  {
    $this->email = $email;
    $this->password = $password;
  }

  public function getSocialNetwork(): SocialNetworkConnector
  {
    return new LinkedInConnector($this->email, $this->password);
  }
}

// La interfaz del producto declara comportamientos de varios tipos de productos.

interface SocialNetworkConnector
{
  public function logIn(): void;

  public function logOut(): void;

  public function createPost($content): void;
}

// Este producto concreto implementa la API de Facebook.

class FacebookConnector implements SocialNetworkConnector
{
  private $login, $password;

  public function __construct(string $login, string $password)
  {
    $this->login = $login;
    $this->password = $password;
  }

  public function logIn(): void
  {
    debuguear("Send HTTP API request to log in user $this->login with " .
      "password $this->password");
  }

  public function logOut(): void
  {
    debuguear("Send HTTP API request to log out user $this->login");
  }

  public function createPost($content): void
  {
    debuguear("Send HTTP API requests to create a post in Facebook timeline.");
  }
}

// Este producto concreto implementa la API de LinkedIn.

class LinkedInConnector implements SocialNetworkConnector
{
  private $email, $password;

  public function __construct(string $email, string $password)
  {
    $this->email = $email;
    $this->password = $password;
  }

  public function logIn(): void
  {
    debuguear("Send HTTP API request to log in user $this->email with " .
      "password $this->password");
  }

  public function logOut(): void
  {
    debuguear("Send HTTP API request to log out user $this->email");
  }

  public function createPost($content): void
  {
    debuguear("Send HTTP API requests to create a post in LinkedIn timeline.");
  }
}

/*El código del cliente puede funcionar con cualquier subclase de SocialNetworkPoster ya que no 
  depende de clases concretas.
*/
function clientCode(SocialNetworkPoster $creator)
{

  $creator->post("Hello world!");
  $creator->post("I had a large hamburger this morning!");
}

/*Durante la fase de inicialización, la aplicación puede decidir con qué red social quiere trabajar,
  crear un objeto de la subclase adecuada y pasarlo al código del cliente.
*/
debuguear("Testing ConcreteCreator1:");
clientCode(new FacebookPoster("john_smith", "******"));


debuguear("Testing ConcreteCreator2:");
clientCode(new LinkedInPoster("john_smith@example.com", "******"));
