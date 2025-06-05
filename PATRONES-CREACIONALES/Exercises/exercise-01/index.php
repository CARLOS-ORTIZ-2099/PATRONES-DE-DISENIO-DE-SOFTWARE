<?php

// index.php

require_once __DIR__ . "/../../../includes/app.php";
//require_once __DIR__ . "/Singleton.php";
require_once __DIR__ . "/Builder.php";

function executeSingleton()
{
  // invocando el metodo desde index.php
  $conf = Config::getInstance();
  $conf->setValue('password', 'password very safe');

  debuguear($conf);
}
// executeSingleton();
