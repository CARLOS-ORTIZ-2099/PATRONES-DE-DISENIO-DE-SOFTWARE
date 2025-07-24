<?php

// interface de estados comunes
interface StateComun
{
  public function Acelerar();
  public function Frenar();
  public function Contacto();
}




// estados concretos

class ApagadoState implements StateComun
{
  // Referencia a la clase de contexto
  private Vehiculo $v;
  public $data = "hola desde ApagadoState";

  // Constructor que inyecta la dependencia en la clase actual
  public function __construct(Vehiculo $v)
  {
    $this->v = $v;
  }

  public function Acelerar(): void
  {
    // Acelerar con el vehiculo apagado no sirve de mucho <img draggable="false" role="img" class="emoji" alt="🙂" src="https://s0.wp.com/wp-content/mu-plugins/wpcom-smileys/twemoji/2/svg/1f642.svg">
    debuguear("ERROR: El vehiculo esta apagado. Efectue el contacto para iniciar");
  }

  public function Frenar(): void
  {
    // Frenar con el vehiculo parado tampoco sirve de mucho...
    debuguear("ERROR: El vehiculo esta apagado. Efectue el contacto para iniciar");
  }

  public function Contacto(): void
  {
    // Comprobamos que el vehiculo disponga de combustible
    if ($this->v->getCombustibleActual() > 0) {
      // El vehiculo arranca -> Cambio de estado
      //estado = PARADO;
      $this->v->setState(new ParadoState($this->v));
      debuguear("El vehiculo se encuentra ahora PARADO");
      $this->v->setVelocidadActual(0);
    } else {
      // El vehiculo no arranca -> Sin combustible
      //estado = SIN COMBUSTIBLE
      $this->v->setState(new SinCombustibleState($this->v));
      debuguear("El vehiculo se encuentra sin combustible");
    }
  }
}


class ParadoState implements StateComun
{
  // Referencia a la clase de contexto
  private Vehiculo $v;

  // Constructor que inyecta la dependencia en la clase actual
  public function __construct(Vehiculo $v)
  {
    $this->v = $v;
  }

  public function Acelerar(): void
  {
    // Comprobamos que el vehiculo disponga de combustible
    if ($this->v->getCombustibleActual() > 0) {
      // El vehiculo se pone en marcha. Aumenta la velocidad y cambiamos de estado
      //estado = EN_MARCHA;
      $this->v->setState(new EnMarchaState($this->v));
      debuguear("El vehiculo se encuentra ahora EN MARCHA");
      $this->v->ModificarVelocidad(10);
      $this->v->ModificarCombustible(-10);
    } else {
      //estado = SIN COMBUSTIBLE
      $this->v->setState(new SinCombustibleState($this->v));
      debuguear("El vehiculo se encuentra ahora SIN COMBUSTIBLE");
    }
  }

  public function Frenar(): void
  {
    // No ocurre nada. Si el vehiculo ya se encuentra detenido, no habra efecto alguno
    debuguear("ERROR: El vehiculo ya se encuentra detenido");
  }

  public function Contacto(): void
  {
    // El vehiculo se apaga
    // estado = APAGADO;
    $this->v->setState(new ApagadoState($this->v));
    debuguear("El vehiculo se encuentra ahora APAGADO");
  }
}


class EnMarchaState implements StateComun
{
  private const int VELOCIDAD_MAXIMA = 200;

  // Referencia a la clase de contexto
  private Vehiculo $v;

  // Constructor que inyecta la dependencia en la clase actual
  public function __construct(Vehiculo $v)
  {
    $this->v = $v;
  }

  public function Acelerar(): void
  {
    if ($this->v->getCombustibleActual() > 0) {
      // Aumentamos la velocidad, permaneciendo en el mismo estado
      if ($this->v->getVelocidadActual() >= self::VELOCIDAD_MAXIMA) {
        debuguear("ERROR: El coche ha alcanzado su velocidad maxima");
        $this->v->ModificarCombustible(-10);
      } else {
        $this->v->ModificarVelocidad(10);
        $this->v->ModificarCombustible(-10);
      }
    } else {
      //estado = SIN COMBUSTIBLE
      $this->v->setState(new SinCombustibleState($this->v));
      debuguear("El vehiculo se ha quedado sin combustible");
    }
  }

  public function Frenar(): void
  {
    // Reducimos la velocidad. Si esta llega a 0, cambiaremos a estado "PARADO"
    $this->v->ModificarVelocidad(-10);
    if ($this->v->getVelocidadActual() <= 0) {
      //estado = PARADO;
      $this->v->setState(new ParadoState($this->v));
      debuguear("El vehiculo se encuentra ahora PARADO");
    }
  }

  public function Contacto(): void
  {
    // No se puede detener el vehiculo en marcha!
    debuguear("ERROR: No se puede cortar el contacto en marcha!");
  }
}


class SinCombustibleState implements StateComun
{
  // Referencia a la clase de contexto
  private Vehiculo $v;

  // Constructor que inyecta la dependencia en la clase actual
  public function __construct(Vehiculo $v)
  {
    $this->v = $v;
  }

  public function Acelerar(): void
  {
    debuguear("ERROR: El vehiculo se encuentra sin combustible");
  }

  public function Frenar(): void
  {
    debuguear("ERROR: El vehiculo se encuentra sin combustible");
  }

  public function Contacto(): void
  {
    debuguear("ERROR: El vehiculo se encuentra sin combustible");
  }
}

// clase Vehiculo(contexto)

class Vehiculo
{
  #region Atributos

  private StateComun $estado; // Estado actual del vehiculo (apagado, parado, en marcha, sin combustible)
  private int $velocidadActual = 0; // Velocidad actual del vehiculo
  private int $combustibleActual = 0; // Cantidad de combustible restante


  #region Constructores

  // El constructor inserta el combustible del que dispondra el vehiculo
  public function __construct(int $combustible)
  {

    $this->combustibleActual = $combustible;

    //Indicar un estado inicial (Apagado)
    $this->estado = new ApagadoState($this);
  }



  #region Properties

  // Asigna o recupera el estado del vehiculo
  /*    public function State Estado
    {
        get { return estado; }
        set { estado = value; }
    } */

  public function getState()
  {
    return $this->estado;
  }
  public function setState($value)
  {
    $this->estado = $value;
  }


  // Asigna o recupera la velocidad actual del vehiculo
  /*     public function  VelocidadActual(): int
    {
        get { return velocidadActual; }
        set { velocidadActual = value; }
    } */

  public function getVelocidadActual(): int
  {
    return $this->velocidadActual;
  }
  public function setVelocidadActual($value)
  {
    $this->velocidadActual = $value;
  }
  // Obtiene la cantidad de combustible actual
  public function getCombustibleActual(): int
  {
    return $this->combustibleActual;
  }





  #region Metodos relacionados con los estados

  // Los metodos del contexto invocaran el metodo de la interfaz State, delegando las operaciones
  // dependientes del estado en las clases que los implementen.
  public function Acelerar(): void
  {
    $this->estado->Acelerar();

    debuguear("Velocidad actual: " . $this->velocidadActual . ". Combustible restante: " . $this->combustibleActual);
  }
  public function Frenar(): void
  {
    $this->estado->Frenar();
  }
  public function Contacto(): void
  {
    $this->estado->Contacto();
  }



  #region Otros metodos

  public function ModificarVelocidad(int $kmh): void
  {
    $this->velocidadActual += $kmh;
  }
  public function ModificarCombustible(int $decilitros): void
  {
    $this->combustibleActual += $decilitros;
  }
}




$v = new Vehiculo(20);

//debuguear($v);
$v->Acelerar();
$v->Contacto();
$v->Acelerar();
$v->Acelerar();
$v->Acelerar();
$v->Frenar();
$v->Frenar();
$v->Frenar();
$v->Frenar();
