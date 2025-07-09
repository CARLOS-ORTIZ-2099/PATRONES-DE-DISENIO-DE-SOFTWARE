<?php

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../../../includes/functions.php";

use exercise_05\interfaces\DocumentInterface;

session_start();

class Usuario
{
  protected $name;
  protected $role;
  public function __construct($name, $role)
  {
    $this->name = $name;
    $this->role = $role;
  }

  public function saveUser()
  {
    $_SESSION['user_test'] = [
      "name" => $this->name,
      "role" => $this->role
    ];
    debuguear($_SESSION);
  }
}
$user1 = new Usuario("juan", "admin");
//debuguear($user1);
$user1->saveUser();
//debuguear($_SESSION);

class DocumentoReal implements DocumentInterface
{
  public $data;

  public function readDocument($path)
  {
    if (file_exists($path)) {
      $archivo = fopen($path, "r");
      while (($linea = fgets($archivo))) {
        debuguear($linea);
      }
      fclose($archivo);
    } else {
      debuguear("⚠️ Archivo no encontrado: $path");
    }
  }
  public function writeDocument($path)
  {
    if (file_exists($path)) {
      $archivo = fopen($path, "a"); // "w" para escribir (sobrescribe), "a" para agregar al final
      if ($archivo) {
        fwrite($archivo, "Primera línea\n");
        fclose($archivo);
        debuguear("Documento escrito correctamente.");
      } else {
        debuguear("❌ No se pudo abrir el archivo para escritura.");
      }
    } else {
      debuguear("ruta invalida");
    }
  }
  public function deleteDocument($path)
  {
    if (file_exists($path)) {
      if (unlink($path)) {
        debuguear("✅ Archivo eliminado correctamente.");
      } else {
        debuguear("❌ No se pudo eliminar el archivo.");
      }
    } else {
      debuguear("⚠️ El archivo no existe.");
    }
  }
  public function createDocument($path)
  {
    if (file_exists($path)) {
      return debuguear("no se puede crear el archivo con ese nombre");
    } else {
      $archivo = fopen($path, "x");
      if ($archivo) {
        fclose($archivo);
        debuguear("✅ Fichero creado con fopen.");
      } else {
        debuguear("🚫 No se pudo abrir el archivo para escritura.");
      }
    }
  }
  public function uploadLargeDocument($path)
  {
    if ($this->data == null) {
      debuguear("Cargando documento pesado...");
      $this->data =  file_get_contents($path);
    }
    $this->show();
  }
  public function show()
  {
    debuguear("mostrando data " . $this->data);
  }
}


class ProxyDocument implements DocumentInterface
{
  protected DocumentInterface $document;

  public function __construct(DocumentInterface $document)
  {
    $this->document = $document;
  }

  public function validateCredentials()
  {
    if (!isset($_SESSION["user_test"])) {
      return ["response" => false, "message" => "inicia session primero"];
    };
    if ($_SESSION["user_test"]["role"] != "admin") {
      return ["response" => false, "message" => "no tienes permisos"];
    }

    return ["response" => true, "tienes permisos"];
  }

  public function readDocument($path)
  {
    $data =  $this->validateCredentials();
    if ($data["response"]) {
      $this->document->readDocument($path);
    } else {
      debuguear($data["message"]);
    }
  }
  public function writeDocument($path)
  {
    $data =  $this->validateCredentials();
    if ($data["response"]) {
      $this->document->writeDocument($path);
    } else {
      debuguear($data["message"]);
    }
  }
  public function deleteDocument($path)
  {
    $data =  $this->validateCredentials();
    if ($data["response"]) {
      $this->document->deleteDocument($path);
    } else {
      debuguear($data["message"]);
    }
  }
  public function createDocument($path)
  {
    $data =  $this->validateCredentials();
    if ($data["response"]) {
      $this->document->createDocument($path);
    } else {
      debuguear($data["message"]);
    }
  }
  public function uploadLargeDocument($path)
  {
    $data =  $this->validateCredentials();
    if ($data["response"]) {
      $this->document->uploadLargeDocument($path);
    } else {
      debuguear($data["message"]);
    }
  }
}

$path1 = "./files/data.txt";
$path2 = "https://jsonplaceholder.typicode.com/comments";
$document1 = new DocumentoReal();
//$document1->uploadLargeDocument($path2);
//$document1->readDocument("./files/data.txt");
//$document1->writeDocument("./files/data.txt");
//$document1->deleteDocument("./files/data.txt");
//$document1->createDocument("./files/data.txt");

$proxy = new ProxyDocument($document1);
$proxy->uploadLargeDocument($path2);
$proxy->uploadLargeDocument($path2);
//$proxy->uploadLargeDocument($path2);

//$proxy->readDocument($path1);
//$proxy->writeDocument($path1);
//$proxy->deleteDocument($path1);
//$proxy->createDocument($path1);
