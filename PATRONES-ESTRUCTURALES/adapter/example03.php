<?php

/* 🧩 Desafío del Adaptador: Integrando Impresoras Antiguas

    Tu aplicación moderna tiene una interfaz estándar llamada ImpresoraModerna, que los nuevos
    dispositivos siguen. Sin embargo, debes integrar una impresora antigua en el sistema que 
    usa métodos distintos y no implementa esta interfaz.

    Tu tarea:
    Crear un Adaptador que permita que la impresora antigua funcione con el sistema que espera 
    ImpresoraModerna.

    El sistema solo debería conocer el adaptador, nunca directamente la impresora antigua.

    Objetivos:
    Que el código cliente pueda llamar a métodos como imprimirDocumento($contenido) sin saber si
    es una impresora nueva o una adaptada.

    Asegurar que no se modifique el código original de la impresora antigua. 
*/


interface ImpresoraModernaInterface
{

  public function imprimir();
  public function escanear();
  public function imprimirDobleHoja();
}

class ImpresoraNueva implements ImpresoraModernaInterface
{
  public function imprimir()
  {
    debuguear("imprimiendo moderna");
  }
  public function escanear()
  {
    debuguear("escaneando moderna");
  }
  public function imprimirDobleHoja()
  {
    debuguear("imprimiendo doble cara moderna");
  }
}

class ImpresoraAntigua
{
  public function imprimir()
  {
    debuguear("imprimiendo antigua");
  }
}


class AdaptadorAntiguaModerna implements ImpresoraModernaInterface
{
  public $impresoraAntigua;
  public function __construct(ImpresoraAntigua $impresoraAntigua)
  {
    $this->impresoraAntigua = $impresoraAntigua;
  }

  public function imprimir()
  {
    $this->impresoraAntigua->imprimir();
  }
  public function escanear()
  {
    debuguear("la impresora antigua no escanea");
  }

  public function imprimirDobleHoja()
  {
    debuguear("la impresora antigua no imprime a doble cara automaticamente");
  }
}




function clientCode(ImpresoraModernaInterface $impresora)
{

  $impresora->imprimir();
  $impresora->escanear();
  $impresora->imprimirDobleHoja();
}
$impresora_nueva = new ImpresoraNueva;
clientCode($impresora_nueva);
$impresora_antigua = new AdaptadorAntiguaModerna(new ImpresoraAntigua);
clientCode($impresora_antigua);
