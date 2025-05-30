<?php

function debuguear($data, $die = null)
{
  echo "<pre/>";
  var_dump($data);
  echo "<pre/>";
  if ($die) {
    die();
  }
}
