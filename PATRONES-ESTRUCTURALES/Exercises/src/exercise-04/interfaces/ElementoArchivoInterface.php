<?php

namespace exercise\interfaces;

/* interface ElementoArchivoInterface
{
  // obtiene el peso del elemento ya sea un fichero o carpeta
  public function obtenerTamaño();
  // simulamos que se hace doble click entonces si es un archivo mostrara su contenido como tal
  // si es un fichero mostrara sus archivos que lo componen
  public function mostrar();

  // hace una copia independientemente de que si es un archivo o un fichero
  public function copiar();

  // inserta documentos (si es una carpeta)
  public function insertar();
  // elimina documentos (si es una carpeta)
  public function remover();
}
*/

abstract class ElementoArchivoInterface
{

  protected $tamanio;
  protected $contenido;
  protected $nombre;
  protected $fechaCreacion;

  public function __construct($tamanio, $nombre, $fechaCreacion)
  {
    $this->tamanio = $tamanio;
    $this->nombre = $nombre;
    $this->fechaCreacion = $fechaCreacion;
  }

  abstract public function obtenerTamaño();
  // simulamos que se hace doble click entonces si es un archivo mostrara su contenido como tal
  // si es un fichero mostrara sus archivos que lo componen
  abstract public function mostrar();

  // hace una copia independientemente de que si es un archivo o un fichero
  abstract public function copiar();

  // inserta documentos (si es una carpeta)
  public function insertar() {}
  // elimina documentos (si es una carpeta)W
  public function remover() {}
}
