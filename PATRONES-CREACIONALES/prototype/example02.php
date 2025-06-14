<?php

// Prototipo.
class Page
{
  private $title;

  private $body;

  private Author $author;

  private $comments = [];

  private \DateTime $date;


  public function __construct(string $title, string $body, Author $author)
  {
    $this->title = $title;
    $this->body = $body;
    $this->author =  $author;
    $this->date = new \DateTime();
    // esto devuelve una llamada recursiva por que un objeto page contiene 
    // una propiedad Author y un author contiene un arreglo de pages
    // y cada page tiene un Author que asu vez contiene un arreglo de pages
    $this->author->addToPage($this);
  }

  public function addComment(string $comment): void
  {
    $this->comments[] = $comment;
  }

  /* Puedes controlar qué datos quieres transferir al objeto clonado.

    Por ejemplo, al clonar una página:
    - Se le asigna un nuevo título "Copia de...".
    - El autor de la página permanece igual. Por lo tanto, conservamos
      la referencia al objeto existente al añadir la página clonada a la
      lista de páginas del autor.
    - No transferimos los comentarios de la página anterior.
    - También adjuntamos un nuevo objeto de fecha a la página.

  */
  public function __clone()
  {
    $this->title = "Copy of " . $this->title;
    $this->author->addToPage($this);
    $this->comments = [];
    $this->date = new \DateTime();
  }
}



class Author
{
  private $name;
  private  $pages = [];

  public function __construct(string $name)
  {
    $this->name = $name;
  }

  public function addToPage(Page $page): void
  {
    $this->pages[] = $page;
  }
}

// El código de cliente.

function clientCode()
{
  $author = new Author("John Smith");
  debuguear($author);

  $page = new Page("Consejo del día", "Mantenga la calma y continúe.", $author);

  debuguear($page);

  $page->addComment("¡Buen consejo, gracias!");
  debuguear($page);

  $draft = clone $page;



  debuguear("Volcado del clon. Nótese que el autor ahora hace referencia a dos objetos.");
  debuguear($draft);
}

clientCode();
