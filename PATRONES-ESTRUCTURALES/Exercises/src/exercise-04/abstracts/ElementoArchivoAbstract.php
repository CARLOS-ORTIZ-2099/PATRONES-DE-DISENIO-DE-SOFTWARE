<?php

namespace exercise\abstracts;
// decidi hacerlo abstracto para compartir ciertas funcionalidades y/o propiedades
abstract class ElementoArchivoAbstract
{

  protected $tamanio = 0;
  protected $nombre;
  protected $fechaCreacion;

  public function __construct($nombre)
  {
    $this->nombre = $nombre;
    $this->fechaCreacion = date('Y-m-d');
  }

  abstract public function obtenerTamaño();
  // simulamos que se hace doble click entonces si es un archivo mostrara su contenido como tal
  // si es un fichero mostrara sus archivos que lo componen
  abstract public function mostrar();

  // hace una copia independientemente de que si es un archivo o un fichero
  abstract public function copiar();

  abstract public function getEstructura();


  // inserta documentos (si es una carpeta)
  public function insertar(ElementoArchivoAbstract $element) {}
  // elimina documentos (si es una carpeta)W
  public function remover(ElementoArchivoAbstract $element) {}
  public function getValues($key)
  {
    return $this->$key;
  }
}
