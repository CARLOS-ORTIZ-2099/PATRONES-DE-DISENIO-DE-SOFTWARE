<?php
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../../../includes/functions.php";


// llamando los namespaces
use App\interfaces\ProcesadorPagoInterface;
use Dotenv\Dotenv;
use Stripe\StripeClient;

// configurando el dotenv
$rout = __DIR__ . "/config/";
$dotenv = Dotenv::createImmutable($rout);
$dotenv->load();
$stripeSecretKey = $_ENV['STRIPE_SECRET'];

// instanciando el cliente stripe
$stripe = new StripeClient($stripeSecretKey);
//debuguear($stripe);


// APLICANDO ADAPTER

// clase que estaba pensada para actuar con mi metodo de pago
class OtroMetodoDePago implements ProcesadorPagoInterface
{
  public function procesarPago(float $monto): string
  {
    debuguear("se esta procesando pago con monto de " . $monto . " dolares");
    return "se esta procesando pago con monto de " . $monto . " dolares";
  }
  public function confirmarPago(string $id): bool
  {
    debuguear("se esta confirmando el pago del id : " . $id);
    return true;
  }
  public function verificarEstado(string $id): string
  {
    debuguear("verificando estado del pago : " . $id);
    return $id;
  }
  public function cancelarPago(string $id): bool
  {
    debuguear("cancelando el pago de la transaccion con id : " . $id);
    return true;
  }
}

// el adaptador que adaptara la clase incompatible stripeCliente para que 
// funcione con el sistema
class StripeAdapter implements ProcesadorPagoInterface
{
  protected StripeClient $stripe;

  public function __construct(StripeClient $stripe)
  {
    $this->stripe = $stripe;
  }

  public function procesarPago(float $monto): string
  {
    // Creamos un PaymentIntent(intento de pago)
    $intent = $this->stripe->paymentIntents->create([
      'amount' => $monto,
      'currency' => 'usd',
      'payment_method_types' => ['card'],
    ]);
    //debuguear($intent);
    return $intent->id;
  }
  public function confirmarPago(string $id): bool
  {
    // Esto le indica a Stripe que use un método de pago simulado.(confirmar pago)
    $response = $this->stripe->paymentIntents->confirm($id, [
      'payment_method' => 'pm_card_visa',
    ]);
    //debuguear($response);
    $response = $response->status === 'succeeded' ? true : false;
    return $response;
  }
  public function verificarEstado(string $id): string
  {
    // verificamos estado de un pago
    $paymentIntent = $this->stripe->paymentIntents->retrieve($id);
    //debuguear($paymentIntent);
    return $paymentIntent->status;
  }
  public function cancelarPago(string $id): bool
  {
    // Cancelar un PaymentIntent si aún no fue confirmado:
    $response = $this->stripe->paymentIntents->cancel($id);
    //debuguear($response);
    $response = $response->status === 'succeeded' ? true : false;
    return $response;
  }
}



// trabajando con lo que espera mi sistema
$otroMetodoDePago =  new OtroMetodoDePago();
//$res1 = $otroMetodoDePago->procesarPago(150);
//$res2 = $otroMetodoDePago->confirmarPago("pi_3ResjmP8KA7d6fIW1YuE19qZ");
//$res3 = $otroMetodoDePago->verificarEstado("pi_3ResjmP8KA7d6fIW1YuE19qZ");
//$res4 = $otroMetodoDePago->cancelarPago("pi_3RerqgP8KA7d6fIW1tYZJvwA");


// adaptando la clase incompatible stripe a lo que espera mi sistema
$stripeAdapter = new StripeAdapter($stripe);
//$res1 = $stripeAdapter->procesarPago(150);
//$res2 = $stripeAdapter->confirmarPago("pi_3ResjmP8KA7d6fIW1YuE19qZ");
//$res3 = $stripeAdapter->verificarEstado("pi_3ResjmP8KA7d6fIW1YuE19qZ");
//$res4 = $stripeAdapter->cancelarPago("pi_3RerqgP8KA7d6fIW1tYZJvwA");




// APLICANDO DECORATOR
/* si a futuro quiero agregar nuevas funcionalidades como hacer un registro 
   de las actividades del usuario o validar los datos de entrada, no tengo que 
   tocar el código previamente hecho, sólo tengo crear un decorador base
   para cada entidad a la que quiera "decorar" y posteriormente decoradores 
   concretos por cada comportamiento a añadir  
*/

abstract class PaymentDecorator implements ProcesadorPagoInterface
{
  protected ProcesadorPagoInterface $payment;

  public function __construct(ProcesadorPagoInterface $payment)
  {
    $this->payment = $payment;
  }
  public function procesarPago(float $monto): string
  {
    return $this->payment->procesarPago($monto);
  }
  public function confirmarPago(string $id): bool
  {
    return $this->payment->confirmarPago($id);
  }
  public function verificarEstado(string $id): string
  {
    return $this->payment->verificarEstado($id);
  }
  public function cancelarPago(string $id): bool
  {
    return $this->payment->cancelarPago($id);
  }
}

class LoguerDecorator extends PaymentDecorator
{
  protected string $fileName = __DIR__ . "/registers/register.json";
  public function procesarPago(float $monto): string
  {
    $idPago = parent::procesarPago($monto);
    // guardar este registro en un fichero
    if ($idPago) {
      $this->registerData("procesar pago");
    }
    return $idPago;
  }
  public function confirmarPago(string $id): bool
  {
    $response = parent::confirmarPago($id);
    // guardar este registro
    if ($response) {
      $this->registerData("confirmar pago");
    }
    return $response;
  }
  public function verificarEstado(string $id): string
  {
    $response = parent::verificarEstado($id);
    // guardar este registro
    if ($response) {
      $this->registerData("verificar estado");
    }
    return $response;
  }
  public function cancelarPago(string $id): bool
  {
    $response = parent::cancelarPago($id);
    // guardar este registro
    $this->registerData("cancelar pago");
    return $response;
  }

  public function registerData($typeOperation)
  {
    $dataText = file_get_contents($this->fileName);
    $dataText = json_decode($dataText, true);
    $dataText[] = [
      "typeOperation" => $typeOperation,
      "date" => date('d/m/Y'),
      "hour" => date('H:i:s')
    ];
    $dataText = json_encode($dataText);
    file_put_contents($this->fileName, $dataText);
  }
}

class ValidatorDecorator extends PaymentDecorator
{
  public function procesarPago(float $monto): string
  {
    if ($monto <= 100) {
      return "tienes que mandar un monto mayor a 100";
    }
    $idPago = parent::procesarPago($monto);
    return $idPago;
  }
  public function confirmarPago(string $id): bool
  {
    if (strlen($id) < 27) {
      return false;
    }
    $response = parent::confirmarPago($id);
    return $response;
  }
  public function verificarEstado(string $id): string
  {
    if (strlen($id) < 27) {
      return "no es un id valido";
    }
    $response = parent::verificarEstado($id);
    return $response;
  }
  public function cancelarPago(string $id): bool
  {
    if (strlen($id) < 27) {
      return false;
    }
    $response = parent::cancelarPago($id);
    return $response;
  }
}


// aplicando decorator

//$loguerDecorator = new LoguerDecorator($otroMetodoDePago);
//debuguear($loguerDecorator);
//debuguear($loguerDecorator->procesarPago(5000));
//debuguear($loguerDecorator->confirmarPago("pi_3RereRP8KA7d6fIW0elnzdjO"));
//debuguear($loguerDecorator->verificarEstado("pi_3RereRP8KA7d6fIW0elnzdjO"));
//debuguear($loguerDecorator->cancelarPago("pi_3RetENP8KA7d6fIW13BI4imc"));

//$validatorDecorator = new ValidatorDecorator($stripeAdapter);
//debuguear($validatorDecorator);
//debuguear($validatorDecorator->procesarPago(50));
//debuguear($validatorDecorator->confirmarPago("pi_3Reuk6P8KA7d6fIW1zHYO6IH"));
//debuguear($validatorDecorator->verificarEstado("d"));
//debuguear($validatorDecorator->cancelarPago("pi_3ReuafP8KA7d6fIW17n6fURA"));


// aplicando decorator compuesto
$loguerDecorator = new LoguerDecorator($stripeAdapter);
$validatorDecorator = new ValidatorDecorator($loguerDecorator);
//debuguear($validatorDecorator);
//debuguear($validatorDecorator->procesarPago(7000));
//debuguear($validatorDecorator->confirmarPago("pi_3Revj7P8KA7d6fIW1VMMcxTK"));
//debuguear($validatorDecorator->verificarEstado("pi_3Revj7P8KA7d6fIW1VMMcxTK"));
//debuguear($validatorDecorator->cancelarPago("pi_3ReuaUP8KA7d6fIW10IvRtsA"));
