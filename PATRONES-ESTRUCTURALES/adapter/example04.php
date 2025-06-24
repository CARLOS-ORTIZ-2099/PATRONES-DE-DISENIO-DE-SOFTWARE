<?php
/* 🧩 Desafío: Adaptando APIs de Pagos
  Tu sistema moderno utiliza una interfaz estándar llamada
  ProcesadorPagoInterface, que define métodos para procesar pagos y verificar
  el estado de las transacciones. Sin embargo, necesitas integrar una API de
  pagos antigua que tiene métodos diferentes y no cumple con esta interfaz. 

  Tu tarea:
  Crear un Adaptador que permita que la API de pagos antigua funcione con el
  sistema que espera ProcesadorPagoInterface.

  Asegúrate de que el cliente solo interactúe con el adaptador y nunca
  directamente con la API antigua.

  Detalles:
  La interfaz moderna (ProcesadorPagoInterface) debe incluir métodos como:

    procesarPago($monto, $moneda)

    verificarEstado($idTransaccion)

  La API antigua tiene métodos como:
    pagar($cantidad)

    estadoTransaccion($codigo)

  Objetivos:
  Traducir las llamadas de la interfaz moderna a los métodos de la API
  antigua.

  No modificar el código original de la API antigua.

  Probar que el adaptador funciona correctamente con el sistema moderno. 

*/



interface ProcesadorPagoInterface
{
  public function procesarPago($monto, $moneda);
  public function verificarEstado($idTransaccion);
}


class ApiPagoModerna implements ProcesadorPagoInterface
{
  public function procesarPago($monto, $moneda)
  {
    debuguear("procesando pago con monto de : " . $monto . " y el tipo de moneda es : " . $moneda);
  }
  public function verificarEstado($idTransaccion)
  {
    debuguear("vefirifando estado de la cuenta con id " . $idTransaccion);
  }
}

// aquí podría implementar los métodos de la interface, pero eso implicaría 
// tener que modificar la clase existente, rompiendo así con el principio de 
// SRP
class ApiPagoAntigua
{
  // estos métodos son equivalentes con los de la clase ApiPagoModerna
  // sin embargo no son compatibles con el código cliente que la utilizara

  // este método es equivalente a procesarPago de la clase ApiPagoModerna
  public function pagar($cantidad)
  {
    debuguear("se esta pagando desde la api antigua el monto de : " . $cantidad);
  }

  // este método es equivalente a verificarEstado de la clase ApiPagoModerna
  public function estadoTransaccion($codigo)
  {
    debuguear("se esta consultando transaccion con el código : " . $codigo);
  }
}


class ApiPagoAntiguaAdapter implements ProcesadorPagoInterface
{
  private $apiPagoAntigua;
  public function __construct(ApiPagoAntigua $apiPagoAntigua)
  {
    $this->apiPagoAntigua = $apiPagoAntigua;
  }

  public function procesarPago($monto, $moneda)
  {
    $this->apiPagoAntigua->pagar($monto);
  }
  public function verificarEstado($codigo)
  {
    $this->apiPagoAntigua->estadoTransaccion($codigo);
  }
}

class Client
{
  protected ProcesadorPagoInterface $procesador;

  public function __construct(ProcesadorPagoInterface $procesador)
  {
    $this->procesador = $procesador;
  }

  public function procesarPago($monto, $moneda)
  {
    $this->procesador->procesarPago($monto, $moneda);
  }

  public function verificarEstado($idTransaccion)
  {
    $this->procesador->verificarEstado($idTransaccion);
  }
}


$client1 = new Client(new ApiPagoModerna);
debuguear($client1);
$client1->procesarPago(2500, 'dolar');
$client1->verificarEstado('159753abc');

// espero un objeto del tipo ProcesadorPagoInterface
$client2 = new Client(new ApiPagoAntiguaAdapter(new ApiPagoAntigua));
debuguear($client2);
$client2->procesarPago(7000, 'pesos');
$client2->verificarEstado('abczxc');
