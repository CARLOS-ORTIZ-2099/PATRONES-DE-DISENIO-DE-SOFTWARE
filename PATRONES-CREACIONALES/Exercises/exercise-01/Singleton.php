<?php
debuguear('Implementar Singleton en la gestión de configuración global');
// Singleton.php
$parameters = [
  'host' => 'localhost',
  'usuario' => 'root',
  'password' => '',
  'name_db' => 'patrones_disenio',
  'table' => 'singleton',
  'idioma' => 'es'
];

class Config
{
  private static  $config;
  private  $data;

  private function __construct($parameters)
  {
    $this->data = $parameters;
  }


  static function getInstance(array $parameters = [])
  {
    //primero comprobar que exista dicho valor para nuestro atributo

    if (self::$config === null) {
      debuguear('entro');
      self::$config = new Config($parameters);
    }

    return self::$config;
  }


  public function showParameters()
  {
    foreach ($this->data as $key => $value) {
      debuguear("la clave es : " . $key . " y el valor es : " . $value);
    }
  }

  public function getValue($key)
  {
    try {
      if (!isset($this->data[$key])) {
        throw new Exception('la clave solicitada no existe');
      }
      return $this->data[$key];
    } catch (Exception $error) {
      return $error->getMessage();
    }
  }

  public function setValue($key, $value = null)
  {
    if ($value != null) {
      $this->data[$key] = $value;
    }
  }
}

// invocando desde Singleton.php
$config = Config::getInstance($parameters);
debuguear($config);

$config->showParameters();

$config->setValue('name_db');
$config->setValue('custom_property', '159753');
debuguear($config->getValue('name_db'));
debuguear($config->getValue('hola'));
