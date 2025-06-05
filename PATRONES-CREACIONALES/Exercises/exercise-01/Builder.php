<?php
/* Proposito para crear un builder :
   - solo pasamos los valores que necesitemos paso a paso
   - evitamos pasar una cantidad enorme de parametros en el constructor
   - para los lenguajes que admiten sobrecarga de metodos podriamos evitar 
   sobre cargar el constructor de la clase
   Separamos la creación del objeto des su representación final 
*/


/*Pasos para crear el Builder
  - Builder abstracto :(la abstracción) aquel que tendra todos aquellos pasos
    de construcción común entre todos los tipos de objetos

  - Builder concreto : este será el que implemente cada uno de los pasos de 
    construcción

  - Director : aquel que "dirige" la construcción com el orden de creacion y 
  que datos se crearan, etc, en algunos casos es opcional  

  - Producto final : este será el objeto final de la construcción

*/



interface BuildDocument
{
  public function buildHeader($value);
  public function buildContent($value);
  public function buildFooter($value);
  public function buildDocumentType();
  public function build();
}


class Document
{
  public $header;
  public $content;
  public $footer;
  public $type;

  public function print() {}
}

class PdfConcrete implements BuildDocument
{
  private Document $document;

  public function __construct()
  {
    $this->reset();
  }
  public function reset()
  {
    $this->document = new Document;
  }
  public function buildHeader($value): self
  {
    $this->document->header = $value;
    return $this;
  }
  public function buildContent($value): self
  {
    $this->document->content = $value;
    return $this;
  }
  public function buildFooter($value): self
  {
    $this->document->footer = $value;
    return $this;
  }
  public function buildDocumentType(): self
  {
    $this->document->type = 'PDF';
    return $this;
  }
  public function build(): Document
  {
    $document = $this->document;
    $this->reset();
    return $document;
  }
}

class WordConcrete implements BuildDocument
{
  private Document $document;

  public function __construct()
  {
    $this->reset();
  }
  public function reset()
  {
    $this->document = new Document;
  }
  public function buildHeader($value): self
  {
    $this->document->header = $value;
    return $this;
  }
  public function buildContent($value): self
  {
    $this->document->content = $value;
    return $this;
  }
  public function buildFooter($value): self
  {
    $this->document->footer = $value;
    return $this;
  }
  public function buildDocumentType(): self
  {
    $this->document->type = 'TXT';
    return $this;
  }
  public function build(): Document
  {
    $document = $this->document;
    $this->reset();
    return $document;
  }
}


// sin usar clase directora

function sinclaseDirectora()
{
  $pdf1 = new PdfConcrete();

  $pdfResult = $pdf1->buildHeader('encabezado pdf')
    ->buildContent('este es el contenido del pdf')
    ->buildFooter('este es el footer del pdf')
    ->buildDocumentType()
    ->build();

  $pdfResult2 =  $pdf1->buildHeader('encabezado pdf')
    ->buildContent('este es el contenido del pdf')
    ->buildDocumentType()
    ->build();

  $pdfResult3 = $pdf1->buildHeader('encabezado pdf')
    ->buildDocumentType()
    ->build();

  debuguear($pdf1);
  debuguear($pdfResult);
  debuguear($pdfResult2);
  debuguear($pdfResult3);
}



// usando clase directora
class Director
{
  private  BuildDocument $document;
  public function __construct(BuildDocument $builder)
  {
    $this->document = $builder;
  }
  public function createDocumentSimple()
  {
    $this->document->buildHeader('encabezado del documento')
      ->buildContent('este es el contenido del documento')
      ->buildDocumentType();
  }
  public function createDocumentComplex()
  {
    $this->document->buildHeader('encabezado del documento')
      ->buildContent('este es el contenido del documento')
      ->buildFooter('este es el footer del documento')
      ->buildDocumentType();
  }
  public function getDocument()
  {
    return $this->document->build();
  }
}

// PDF
$pdf1 = new PdfConcrete;
$directorPdf = new Director($pdf1);

$directorPdf->createDocumentSimple();
$pdfResult = $directorPdf->getDocument();


$directorPdf->createDocumentComplex();
$pdfResult2 = $directorPdf->getDocument();


debuguear($directorPdf);
debuguear($pdfResult);
debuguear($pdfResult2);


// WORD 
$word1 = new WordConcrete;
$directorWord = new Director($word1);

$directorWord->createDocumentSimple();
$wordResult = $directorWord->getDocument();

$directorWord->createDocumentSimple();
$wordResult2 = $directorWord->getDocument();

debuguear($directorWord);
debuguear($wordResult);
debuguear($wordResult2);



debuguear('------------ SECCIÓN DONDE EVALUAMOS LA UTILIDAD DEL PATRON BUILDER ------------');

class Test
{
  public $name;
  public $age;
  public $address;
  public $address2;
  public $address3;
  public $address4;
  public $address5;
  public $address6;
  public $address7;

  // con el patron builder esto es lo que se quiere evitar
  // sobre carga de metodos para los lenguajes que lo soportan
  /*public function __construct($name, $age, $address, $address2, $address3, $address4, $address5, $address6, $address7) {}
    public function __construct($name, $age) {}
    public function __construct($name, $age, $address) {}
    public function __construct($name, $age, $address, $address2) {}
    public function __construct($name, $age, $address, $address2, $address3) {}
    public function __construct($name, $age, $address, $address2, $address3, $address4) {}
  */

  /* ¿ por que en vez de usar builder no creamos propiedades de esta manera, no seria lo mismo ? 
     la respuesta es que Builder es para objetos bien estructurados y predecibles.
     Setters dinámicos son para objetos que cambian según necesidad y no tienen estructura fija.
  */
  public function setValue($key, $value)
  {
    $this->$key = $value;
    return $this;
  }
}

// el patron builderr tambien queier eviitar esto la cantidad excesiva de parametros
// que se le pasan al constructor, y peor aún si es que no todos se van usar

/* $test = new Test('maria', 21, 'calle 123', 'dcs', 'sdcsd', 'sdcsd', 'sdcsd', 'sdcds', 'sd');
   $test2 = new Test('sdcs', 'sdcds', 'sdc', 'sdcds');
   $test3 = new Test('sdcs', 'sdcds');
*/


// ESTO FUNCIONA PERO ES PARA OBJETOS MAS DINAMICOS SIN ESTRUCTURA FIJA
$test = new Test;
$test->setValue('name', 'juan')->setValue('age', 21);

$test2 = new Test;
$test2->setValue('address4', '4444');

$test3 = new Test;
$test3->setValue('address5', '5555')->setValue('address6', '666');

$test4 = new Test;
$test4->setValue('address5', '5555')->setValue('address6', '666');

debuguear($test);
debuguear($test2);
debuguear($test3);
