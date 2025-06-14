<?php

class EquiposPrevios
{
  public $equipos = [];

  public function __construct($equipos)
  {
    foreach ($equipos as $equipo) {
      $this->equipos[] = $equipo;
    }
  }
}

class Jugador
{
  public $nombre;
  public $edad;
  public $equipo;
  public $posición;
  public $equiposPrevios;

  public function __construct($nombre, $edad, $equipo, $posición, $equiposPrevios)
  {
    $this->nombre = $nombre;
    $this->edad = $edad;
    $this->equipo = $equipo;
    $this->posición = $posición;
    $this->equiposPrevios = $equiposPrevios;
  }

  public function __clone()
  {
    debuguear("clonación exitosa");
    $this->equiposPrevios = clone $this->equiposPrevios;
  }
}

$jugador1 = new Jugador(
  'dembele',
  28,
  'psg',
  'delantero',
  new EquiposPrevios(['dortmund', 'barcelona'])
);

$jugador2 = new Jugador(
  'mbappe',
  26,
  'real madrid',
  'delantero',
  new EquiposPrevios(['monaco', 'psg'])
);



$jugador1Clone = clone $jugador1;
$jugador2Clone = clone $jugador2;

$jugador1Clone->posición = 'cambiado';
$jugador1Clone->equiposPrevios->equipos[0] = 'sp portugal';

$jugador2Clone->posición = 'cambiado';

debuguear($jugador1);
debuguear($jugador2);

debuguear($jugador1Clone);
debuguear($jugador2Clone);
