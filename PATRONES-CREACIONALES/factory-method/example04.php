<?php

// Aplicando el simple Factory 
/* La diferencia es que aqui no tenemos subclases, solamente una serie de
   condicionales que según el valor del parametro se crea uno u otro objeto
*/

// definiendo el producto 
interface DocumentInterface
{
  public function openDocument(): bool;
  public function closeDocument(): bool;
  public function readDocument();
  public function writeDocument();
}


// definiendo los productos concretos

class ExcelDocument implements DocumentInterface
{
  public function openDocument(): bool
  {
    debuguear('abriendo documento excel');
    return true;
  }
  public function closeDocument(): bool
  {
    debuguear('cerrando documento excel');
    return true;
  }
  public function readDocument()
  {
    debuguear('leyendo documento excel');
  }
  public function writeDocument()
  {
    debuguear('escribiendo documento excel');
  }
}

class TxtDocument implements DocumentInterface
{
  public function openDocument(): bool
  {
    debuguear('abriendo documento txt');
    return true;
  }
  public function closeDocument(): bool
  {
    debuguear('cerrando documento txt');
    return true;
  }
  public function readDocument()
  {
    debuguear('leyendo documento txt');
  }
  public function writeDocument()
  {
    debuguear('escribiendo documento txt');
  }
}

class JsonDocument implements DocumentInterface
{
  public function openDocument(): bool
  {
    debuguear('abriendo documento json');
    return true;
  }
  public function closeDocument(): bool
  {
    debuguear('cerrando documento json');
    return true;
  }
  public function readDocument()
  {
    debuguear('leyendo documento json');
  }
  public function writeDocument()
  {
    debuguear('escribiendo documento json');
  }
}


class XmlDocument implements DocumentInterface
{
  public function openDocument(): bool
  {
    debuguear('abriendo documento xml');
    return true;
  }
  public function closeDocument(): bool
  {
    debuguear('cerrando documento xml');
    return true;
  }
  public function readDocument()
  {
    debuguear('leyendo documento xml');
  }
  public function writeDocument()
  {
    debuguear('escribiendo documento xml');
  }
}

// definiendo las fabricas creadoras
abstract class FactoryDocument
{

  public static function createDocument(string $type): ?DocumentInterface
  {
    return match (strtolower($type)) {
      'excel' => new ExcelDocument(),
      'txt' => new TxtDocument(),
      'json' => new JsonDocument(),
      'xml' => new XmlDocument(),
      default => null,
    };
  }

  // otros métodos ...
}


// definiendo el código cliente

class Client
{
  private DocumentInterface $document;

  public function __construct(DocumentInterface $document)
  {
    $this->document = $document;
  }

  public function useDocument()
  {
    $this->document->openDocument();
    $this->document->closeDocument();
    $this->document->readDocument();
    $this->document->writeDocument();
  }
}

$document1 = FactoryDocument::createDocument('Excel');
if ($document1) {
  $client1 = new Client($document1);
  $client1->useDocument();
}

debuguear("-------------");

$document2 = FactoryDocument::createDocument('Json');
if ($document2) {
  $client2 = new Client($document2);
  $client2->useDocument();
}


debuguear("-------------");
$document3 = FactoryDocument::createDocument('xml');
if ($document3) {
  $client3 = new Client($document3);
  $client3->useDocument();
}
