<?php

/* La interfaz Component declara un método de filtrado que debe ser
   implementado por todos los componentes y decoradores concretos.
*/
interface InputFormat
{
  public function formatText(string $text): string;
}

/* El componente concreto es un elemento central de la decoración.
   Contiene el texto original, tal cual, sin filtros ni formatos.
*/
class TextInput implements InputFormat
{
  public function formatText(string $text): string
  {
    return $text;
  }
}

/*  La clase base Decorador no contiene ninguna lógica real de filtrado ni
    formato. Su propósito principal es implementar la infraestructura básica
    de decoración: un campo para almacenar un componente encapsulado u otro 
    decorador y el método de formato básico que delega la tarea al objeto 
    encapsulado. Las subclases realizan el verdadero trabajo de formateo.
*/
class TextFormat implements InputFormat
{

  protected $inputFormat;

  public function __construct(InputFormat $inputFormat)
  {
    $this->inputFormat = $inputFormat;
  }

  // El decorador delega todo el trabajo a un componente envuelto.
  public function formatText(string $text): string
  {
    return $this->inputFormat->formatText($text);
  }
}

// Este concreto decorativo elimina todas las etiquetas HTML del texto dado.
class PlainTextFilter extends TextFormat
{
  public function formatText(string $text): string
  {
    $text = parent::formatText($text);
    return strip_tags($text) . " very go";
  }
}

/* Este decorador concreto elimina únicamente las etiquetas y atributos 
   HTML peligrosos que puedan provocar una vulnerabilidad XSS.
*/
class DangerousHTMLTagsFilter extends TextFormat
{
  private $dangerousTagPatterns = [
    "|<script.*?>([\s\S]*)?</script>|i", // ...
  ];

  private $dangerousAttributes = [
    "onclick",
    "onkeypress", // ...
  ];


  public function formatText(string $text): string
  {
    $text = parent::formatText($text);

    foreach ($this->dangerousTagPatterns as $pattern) {
      $text = preg_replace($pattern, '', $text);
    }

    foreach ($this->dangerousAttributes as $attribute) {
      $text = preg_replace_callback('|<(.*?)>|', function ($matches) use ($attribute) {
        $result = preg_replace("|$attribute=|i", '', $matches[1]);
        return "<" . $result . ">";
      }, $text);
    }

    return $text . " html";
  }
}

// Este decorador concreto proporciona una conversión rudimentaria de Markdown a HTML.
class MarkdownFormat extends TextFormat
{
  public function formatText(string $text): string
  {
    $text = parent::formatText($text);

    //Elementos de bloque de formato.
    $chunks = preg_split('|\n\n|', $text);
    foreach ($chunks as &$chunk) {
      // Format headers.
      if (preg_match('|^#+|', $chunk)) {
        $chunk = preg_replace_callback('|^(#+)(.*?)$|', function ($matches) {
          $h = strlen($matches[1]);
          return "<h$h>" . trim($matches[2]) . "</h$h>";
        }, $chunk);
      } // Dar formato a los párrafos.
      else {
        $chunk = "<p>$chunk</p>";
      }
    }
    $text = implode("\n\n", $chunks);

    // Formatear elementos en línea.
    $text = preg_replace("|__(.*?)__|", '<strong>$1</strong>', $text);
    $text = preg_replace("|\*\*(.*?)\*\*|", '<strong>$1</strong>', $text);
    $text = preg_replace("|_(.*?)_|", '<em>$1</em>', $text);
    $text = preg_replace("|\*(.*?)\*|", '<em>$1</em>', $text);

    return $text . " markdown";
  }
}


/* El código del cliente podría ser parte de un sitio web real, que   
   renderiza contenido generado por el usuario. Dado que funciona con 
   formateadores a través de la interfaz de componentes, no le importa si 
   obtiene un objeto de componente simple o uno decorado.
*/
function displayCommentAsAWebsite(InputFormat $format, string $text)
{
  debuguear($format->formatText($text));
}

/* Los formateadores de entrada son muy útiles al trabajar con contenido 
   generado por el usuario.
   Mostrar dicho contenido "tal cual" puede ser muy peligroso, especialmente 
   cuando usuarios anónimos pueden generarlo (por ejemplo, comentarios). Su 
   sitio web no solo corre el riesgo de recibir una gran cantidad de enlaces 
   spam, sino que también puede estar expuesto a ataques XSS.
*/
$dangerousComment = <<<HERE
Hello! Nice blog post!
Please visit my <a href='http://www.iwillhackyou.com'>homepage</a>.
<script src="http://www.iwillhackyou.com/script.js">
  performXSSAttack();
</script>
HERE;

// Representación ingenua de comentarios (insegura).
$naiveInput = new TextInput();
debuguear("El sitio web muestra los comentarios sin filtrar (no seguro):");
displayCommentAsAWebsite($naiveInput, $dangerousComment);


// Representación filtrada de comentarios (segura).
$filteredInput = new PlainTextFilter($naiveInput);
debuguear("El sitio web muestra los comentarios después de eliminar todas las etiquetas (seguro):");
displayCommentAsAWebsite($filteredInput, $dangerousComment);



/* El decorador permite combinar múltiples formatos de entrada para obtener 
   un control preciso sobre el contenido renderizado.
*/
$dangerousForumPost = <<<HERE
# Welcome

This is my first post on this **gorgeous** forum.

<script src="http://www.iwillhackyou.com/script.js">
  performXSSAttack();
</script>
HERE;

// Representación de publicaciones ingenuas (inseguras, sin formato).
$naiveInput = new TextInput();
debuguear("El sitio web muestra una publicación del foro sin filtrar ni formatear (inseguro, feo):");
displayCommentAsAWebsite($naiveInput, $dangerousForumPost);


/* Formateador Markdown + filtrado de etiquetas peligrosas (seguras, 
   bonitas).
*/
$text = new TextInput();
$markdown = new MarkdownFormat($text);
$filteredInput = new DangerousHTMLTagsFilter($markdown);
debuguear("El sitio web muestra una publicación del foro después de traducir el marcado Markdown y filtrar algunas etiquetas y atributos HTML peligrosos (seguro y atractivo):");
displayCommentAsAWebsite($filteredInput, $dangerousForumPost);
