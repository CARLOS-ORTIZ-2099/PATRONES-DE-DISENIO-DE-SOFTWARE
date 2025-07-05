<?php
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../../../includes/functions.php";

// use exercise\interfaces\ElementoArchivoInterface;
use exercise\abstracts\ElementoArchivoAbstract;

class Archivo extends ElementoArchivoAbstract
{
  protected $contenido;

  public function __construct($nombre, $contenido = null)
  {
    parent::__construct($nombre);
    if ($contenido) {
      $this->contenido = $contenido;
      $this->tamanio = strlen($contenido) * 2;
    }
  }
  public function __clone()
  {
    debuguear("clonando");
  }

  public function obtenerTamaño()
  {
    return $this->tamanio;
  }
  public function mostrar()
  {
    return $this->contenido;
  }
  public function copiar()
  {
    return clone $this;
  }

  public function getEstructura($nivel = 0)
  {
    debuguear("el nivel de el archivo es " . $nivel);
    $nombre = "-📄" . $this->nombre;
    $nombre = str_pad($nombre, strlen($nombre) + $nivel, " ", STR_PAD_LEFT);
    return $nombre;
  }


  // el achivo no se le puede insertar ni remover contenido por lo cual
  // estos métodos quedarían inhabilitados
  public function insertar(ElementoArchivoAbstract $element)
  {
    try {
      throw new BadMethodCallException("no es un objeto compuesto para insertar");
    } catch (BadMethodCallException $e) {
      return $e->getMessage();
    }
  }

  public function remover(ElementoArchivoAbstract $element)
  {
    try {
      throw new BadMethodCallException("no es un objeto compuesto para remover");
    } catch (BadMethodCallException $e) {
      return $e->getMessage();
    }
  }
}


$archivo1 = new Archivo('archivo1.txt', "mundo");
//debuguear($archivo1);
//debuguear($archivo1->mostrar());
$archivo2 = new Archivo("archivo2.xls", "hola como estas");



class Carpeta extends ElementoArchivoAbstract
{

  protected $files = [];

  public function __clone()
  {
    debuguear("clonando carpetas");
  }

  public function obtenerTamaño()
  {
    $total = 0;
    foreach ($this->files as $file) {
      $total += $file->obtenerTamaño();
    }
    return $total;
  }
  public function mostrar()
  {
    $data = null;
    foreach ($this->files as $file) {
      $data .= $file->mostrar() . "\n";
    }
    return $data;
  }

  public function copiar()
  {
    $clon = clone $this;
    $clon->files = [];
    foreach ($this->files as $file) {
      $clon->insertar($file);
    }
    return $clon;
  }

  public function getEstructura($nivel = 0)
  {
    debuguear("el nivel de la carpeta es " . $nivel);
    $name = "";
    if ($nivel > 0) {
      $name =  "-📁" . $this->nombre;
      $name = str_pad($name, strlen($name) + $nivel, " ", STR_PAD_LEFT);
    }
    $nivel += 1;
    foreach ($this->files as $file) {
      $name .= "\n" . $file->getEstructura($nivel) . "\n";
    }
    return $name;
  }


  public function insertar(ElementoArchivoAbstract $element)
  {
    $this->files[] =  $element->copiar();
  }
  public function remover(ElementoArchivoAbstract $element)
  {

    foreach ($this->files as $index => $archivo) {
      if ($archivo->getValues("nombre") === $element->getValues("nombre")) {
        unset($this->files[$index]);
        $this->files = array_values($this->files);
        return true;
      }

      if ($archivo instanceof Carpeta) {
        if ($archivo->remover($element)) {
          return true;
        }
      }
    }

    return false;
  }
}
// carpeta con 2 archivos
$carpeta1 = new Carpeta("carpeta1");
$carpeta1->insertar($archivo1);
$carpeta1->insertar($archivo2);
//debuguear($carpeta1);

// carpeta con 1 archivo
$carpeta2 = new Carpeta("carpeta2");
$carpeta2->insertar($archivo1);
$test = new Archivo("test.txt", "test");
$carpeta2->insertar($test);
//debuguear($carpeta2);

// carpeta con 2 archivos y con una carpeta con 1 archivo
$carpeta1->insertar($carpeta2);
//debuguear($carpeta1);

$carpeta3 = $carpeta1->copiar();
//debuguear($carpeta3);

$carpeta4 = new Carpeta("carpeta 4");
$carpeta4->insertar($archivo1);
$carpeta4->insertar($carpeta1);
$carpeta4->insertar($archivo1);
$carpeta4->insertar($archivo1);

//debuguear($carpeta4);
//debuguear($carpeta4->getEstructura());


$clientCode = function (ElementoArchivoAbstract $element) use ($test) {
  debuguear($element);
  $archivoPrueba = new Archivo("archivo prueba", "bienvenido");

  $tamanio = $element->obtenerTamaño();
  debuguear("tamaño : " . $tamanio);

  $message = $element->mostrar();
  debuguear("mensaje : " . $message);


  $insertar = $element->insertar($archivoPrueba);
  debuguear("container : " . $insertar);


  $estructura = $element->getEstructura();
  debuguear("estructura : " . $estructura);

  $tamanio = $element->obtenerTamaño();
  debuguear("tamaño : " . $tamanio);

  $eliminar = $element->remover($test);
  debuguear("container : " . $eliminar);

  $tamanio = $element->obtenerTamaño();
  debuguear("tamaño : " . $tamanio);

  $estructura = $element->getEstructura();
  debuguear("estructura : " . $estructura);
};
//clientCode($archivo1);
//$clientCode($carpeta1);


// implementando facade

class FileManagerFacade
{
  protected ElementoArchivoAbstract $element;

  public function __construct(ElementoArchivoAbstract $element)
  {
    $this->element = $element;
  }

  public function mostrarEstructura()
  {
    debuguear($this->element->getEstructura());
  }
  public function mostrarMensaje()
  {
    debuguear($this->element->mostrar());
  }
  public function mostrarTamanio()
  {
    debuguear($this->element->obtenerTamaño());
  }
}

debuguear("------ implementando facade ------");
$facade = new FileManagerFacade($carpeta1);
$facade->mostrarEstructura();
$facade->mostrarMensaje();
$facade->mostrarTamanio();
