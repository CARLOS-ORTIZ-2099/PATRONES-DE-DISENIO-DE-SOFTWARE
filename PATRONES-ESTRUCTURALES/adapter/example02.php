<?php

/* La interfaz objetivo representa la interfaz que las clases de su 
   aplicación * ya siguen.
*/
interface Notification
{
  public function send(string $title, string $message);
}

/* Aquí tienes un ejemplo de la clase existente que sigue a la interfaz
   Target.
   Lo cierto es que muchas aplicaciones reales podrían no tener esta interfaz 
   claramente definida.
   Si te encuentras en esa situación, lo mejor sería extender el Adaptador 
   desde una de las clases existentes de tu aplicación. Si esto resulta 
   complicado (por ejemplo, si SlackNotification no parece una subclase de 
   EmailNotification), entonces extraer una interfaz debería ser el primer 
   paso.
*/
class EmailNotification implements Notification
{
  private $adminEmail;

  public function __construct(string $adminEmail)
  {
    $this->adminEmail = $adminEmail;
  }

  public function send(string $title, string $message): void
  {
    mail($this->adminEmail, $title, $message);
    debuguear("Sent email with title '$title' to '{$this->adminEmail}' that says '$message'.");
  }
}

/* El Adaptado es una clase útil, incompatible con la interfaz de Destino. No 
   se puede modificar el código de la clase para que siga la interfaz de 
   Destino, ya que el código podría ser proporcionado por una biblioteca de 
   terceros.
*/
class SlackApi
{
  private $login;
  private $apiKey;

  public function __construct(string $login, string $apiKey)
  {
    $this->login = $login;
    $this->apiKey = $apiKey;
  }

  public function logIn(): void
  {
    // Envía una solicitud de autenticación al servicio web de Slack.
    debuguear("Logged in to a slack account '{$this->login}'.");
  }

  public function sendMessage(string $chatId, string $message): void
  {
    // Envía una solicitud de publicación de mensaje al servicio web de Slack.
    debuguear("Posted following message into the '$chatId' chat: '$message'.");
  }
}

/* El adaptador es una clase que vincula la interfaz Target y la clase 
   Adaptado. * En este caso, permite que la aplicación envíe notificaciones 
   mediante la API de Slack.
*/
class SlackNotification implements Notification
{
  private $slack;
  private $chatId;

  public function __construct(SlackApi $slack, string $chatId)
  {
    $this->slack = $slack;
    $this->chatId = $chatId;
  }

  /* Un adaptador no solo es capaz de adaptar interfaces, sino que también 
     puede * convertir los datos entrantes al formato requerido por el 
     adaptado.
  */
  public function send(string $title, string $message): void
  {
    $slackMessage = "#" . $title . "# " . strip_tags($message);
    $this->slack->logIn();
    $this->slack->sendMessage($this->chatId, $slackMessage);
  }
}

/* El código del cliente puede funcionar con cualquier clase que siga la 
   interfaz de destino.
*/
function clientCode(Notification $notification)
{
  debuguear($notification->send(
    "Website is down!",
    "<strong style='color:red;font-size: 50px;'>Alert!</strong> " .
      "Our website is not responding. Call admins and bring it up!"
  ));
}

debuguear("El código del cliente está diseñado correctamente y funciona con notificaciones por correo electrónico:");
$notification = new EmailNotification("developers@example.com");
clientCode($notification);



debuguear("El mismo código de cliente puede funcionar con otras clases a través del adaptador:");
$slackApi = new SlackApi("example.com", "XXXXXXXX");
$notification = new SlackNotification($slackApi, "Example.com Developers");
clientCode($notification);
