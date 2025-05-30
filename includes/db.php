<?php

// conexion a mysqli

$db = new mysqli('localhost', 'root', '', 'patrones_disenio');

if (!$db) {
  die('fallo la conexión');
}
