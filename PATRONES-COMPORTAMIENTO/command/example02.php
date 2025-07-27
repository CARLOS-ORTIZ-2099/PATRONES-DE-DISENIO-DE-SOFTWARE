<?php

/* La interfaz de comando declara el método de ejecución principal, así como 
   varios métodos auxiliares para recuperar los metadatos de un comando.
*/
interface Command
{
  public function execute(): void;

  public function getId(): int;

  public function getStatus(): int;
}

/* El comando base de web scraping define la infraestructura básica de 
   descarga, común a todos los comandos concretos de web scraping.
 */
abstract class WebScrapingCommand implements Command
{
  public $id;
  public $status = 0;
  // URL para scraping.
  public $url;

  public function __construct(string $url)
  {
    $this->url = $url;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getStatus(): int
  {
    return $this->status;
  }

  public function getURL(): string
  {
    return $this->url;
  }

  /* Dado que los métodos de ejecución de todos los comandos de web scraping 
     son muy similares, podemos proporcionar una implementación 
     predeterminada y permitir que las subclases los anulen si es necesario.
    ¡Psst! Un lector atento podría detectar otro patrón de comportamiento en 
     acción aquí.
  */
  public function execute(): void
  {
    $html = $this->download();
    $this->parse($html);
    $this->complete();
  }

  public function download(): string
  {
    $html = file_get_contents($this->getURL());
    // echo $html;
    debuguear("WebScrapingCommand: Downloaded {$this->url}");

    return $html;
  }

  abstract public function parse(string $html): void;

  public function complete(): void
  {
    $this->status = 1;
    Queue::get()->completeCommand($this);
  }
}

// El comando concreto para scrapear la lista de géneros de películas.
class IMDBGenresScrapingCommand extends WebScrapingCommand
{
  public function __construct()
  {
    $this->url = "https://www.imdb.com/feature/genre/";
  }

  /* Extraiga todos los géneros y sus URL de búsqueda de la página:
     https://www.imdb.com/feature/genre/
  */
  public function parse($html): void
  {
    preg_match_all("|href=\"(https://www.imdb.com/search/title\?genres=.*?)\"|", $html, $matches);
    debuguear("IMDBGenresScrapingCommand: Discovered " . count($matches[1]) . " genres.");
    // matches es un arreglo que necesita ser iterado y por cada iteración 
    // ejecutaremos el método estatico get de la clase Queque
    foreach ($matches[1] as $genre) {
      Queue::get()->add(new IMDBGenrePageScrapingCommand($genre));
    }
  }
}

// El comando concreto para scrapear la lista de películas de un género específico.
class IMDBGenrePageScrapingCommand extends WebScrapingCommand
{
  private $page;

  public function __construct(string $url, int $page = 1)
  {
    parent::__construct($url);
    $this->page = $page;
  }

  public function getURL(): string
  {
    return $this->url . '?page=' . $this->page;
  }

  /* Extrae todas las películas de una página como esta:
     https://www.imdb.com/search/title?genres=sci-fi&explore=title_type,genres
  */
  public function parse(string $html): void
  {
    preg_match_all("|href=\"(/title/.*?/)\?ref_=adv_li_tt\"|", $html, $matches);
    debuguear("IMDBGenrePageScrapingCommand: Discovered " . count($matches[1]) . " movies.");

    // matches es un arreglo que necesita ser iterado y por cada iteración 
    // ejecutaremos el método estatico get de la clase Queque
    foreach ($matches[1] as $moviePath) {
      $url = "https://www.imdb.com" . $moviePath;
      Queue::get()->add(new IMDBMovieScrapingCommand($url));
    }

    // Analizar la URL de la página siguiente.
    if (preg_match("|Next &#187;</a>|", $html)) {
      Queue::get()->add(new IMDBGenrePageScrapingCommand($this->url, $this->page + 1));
    }
  }
}

// El comando Concrete para scrapear los detalles de la película.
class IMDBMovieScrapingCommand extends WebScrapingCommand
{
  /* Obtén la información de la película en una página como esta:
   * https://www.imdb.com/title/tt4154756/
   */
  public function parse(string $html): void
  {
    if (preg_match("|<h1 itemprop=\"name\" class=\"\">(.*?)</h1>|", $html, $matches)) {
      $title = $matches[1];
    }
    debuguear("IMDBMovieScrapingCommand: Parsed movie $title.");
  }
}


/* La clase Queue actúa como un invocador. Apila los objetos de comando y 
   los ejecuta uno por uno. Si la ejecución del script finaliza 
   repentinamente, la cola y todos sus comandos se pueden restaurar 
   fácilmente, sin necesidad de repetir todos los comandos ejecutados.

   Tenga en cuenta que esta es una implementación muy básica de la cola de 
   comandos, que almacena los comandos en una base de datos SQLite local. 
   Existen docenas de soluciones robustas de cola disponibles para su uso en 
   aplicaciones reales.
*/
class Queue
{
  private $db;

  public function __construct()
  {
    $this->db = new mysqli("localhost", "root", null, "patrones_disenio");

    $query = "CREATE TABLE IF NOT EXISTS commands (
      id  INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
      command TEXT,
      status INTEGER
    )";
    $this->db->query($query);
  }

  public function isEmpty(): bool
  {

    $query = "SELECT COUNT(id) AS total FROM commands WHERE status = 0";
    $result = $this->db->query($query);

    if ($row = $result->fetch_assoc()) {
      $bolean = ((int)$row["total"] === 0);
      debuguear($bolean);
      return $bolean;
    }
  }

  public function add(Command $command): void
  {

    $query = "INSERT INTO commands (command, status) VALUES (?, ?)";

    $stmt = $this->db->prepare($query);
    // convertimos un objeto en un texto plano para guardar en bd
    $serialized = base64_encode(serialize($command));
    $status     = $command->getStatus();

    $stmt->bind_param("ss", $serialized, $status);

    $stmt->execute();
    $stmt->close();
  }

  public function getCommand(): Command
  {
    // obtengo el registro con status igual a cero(que será el último objeto en insertarse a la DB)
    $query = "SELECT * FROM commands WHERE status = 0 LIMIT 1";
    $result = $this->db->query($query);
    $command = null;
    if ($row = $result->fetch_assoc()) {
      // descerializamos el texto plano a su valor original
      $command = unserialize(base64_decode($row["command"]));
      $command->id = $row["id"];
    }
    return $command;
  }

  public function completeCommand(Command $command): void
  {


    $query = "UPDATE commands SET status = ? WHERE id = ?";
    $stmt = $this->db->prepare($query);

    if ($stmt) {
      $status = $command->getStatus();
      $id = $command->getId();
      $stmt->bind_param("ii", $status, $id);
      $stmt->execute();
      $stmt->close();
    } else {
      // Manejo de error
      error_log("Fallo al preparar la consulta: " . $this->db->error);
    }
  }

  public function work(): void
  {
    while (!$this->isEmpty()) {
      $command = $this->getCommand();
      $command->execute();
    }
  }

  // Para nuestra comodidad, el objeto Cola es un Singleton.
  public static function get(): Queue
  {
    // creando variable estatica 
    /* entonces se podria decir que esta forma de declarar variables  
       estaticas es util para cuando no queremos "ensuciar" nuestras clases 
       de propiedades estaticas que no necesariamente se vallan a compartir 
       para todas las intancias de dicha clase, si no solamente para un 
       método o función en especifico  
    */
    static $instance;
    if (!$instance) {
      debuguear("entro aqui");
      $instance = new Queue();
    }

    return $instance;
  }
}

// El código de cliente.

$queue = Queue::get();
$queue = Queue::get();
debuguear($queue);
if ($queue->isEmpty()) {
  debuguear("no hay nada aun");
  $queue->add(new IMDBGenresScrapingCommand());
}

$queue->work();
