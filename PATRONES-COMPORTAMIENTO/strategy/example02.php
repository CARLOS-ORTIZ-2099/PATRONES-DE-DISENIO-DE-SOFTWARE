<?php

/* Este es el enrutador y controlador de nuestra aplicación. Al recibir una 
   solicitud, esta clase decide qué comportamiento debe ejecutarse. Cuando la 
   aplicación recibe una solicitud de pago, la clase OrderController también 
   decide qué método de pago debe utilizar para procesarla. Por lo tanto, la 
   clase actúa como Contexto y Cliente simultáneamente.
*/
class OrderController // Contexto | Cliente
{
  // Manejar solicitudes POST.
  public function post(string $url, array $data)
  {
    debuguear("Controller: POST request to $url with " . json_encode($data));
    // parse_url() en PHP se usa para descomponer una URL en sus partes (esquema, host, ruta, query, etc.).
    $path = parse_url($url, PHP_URL_PATH);
    //preg_match se usa para buscar coincidencias con expresiones regulares dentro de una cadena.
    if (preg_match('#^/orders?$#', $path, $matches)) {
      $this->postNewOrder($data);
    } else {
      debuguear("Controller: 404 page");
    }
  }

  // Manejar solicitudes GET
  public function get(string $url): void
  {
    debuguear("Controller: GET request to $url");
    // extrae solo una parte especifica de las rutas
    $path = parse_url($url, PHP_URL_PATH);
    $query = parse_url($url, PHP_URL_QUERY);
    // convertimos dicho string extraido en un array asociativo que lo asignamos a $data
    debuguear("la ruta es : " . $path);
    debuguear("la query es : " . $query);
    $data = [];
    if ($query) {
      parse_str($query, $data);
    }

    if (preg_match('#^/orders?$#', $path, $matches)) {
      debuguear("entro orders :)");
      $this->getAllOrders();
    } elseif (preg_match('#^/order/([0-9]+?)/payment/([a-z]+?)(/return)?$#', $path, $matches)) {

      debuguear("entro order :)");
      debuguear($matches);
      $order = Order::get($matches[1]);

      /* El método de pago (estrategia) se selecciona según el valor que 
         se pasa junto con la solicitud. devuelve una estrategia
      */
      $paymentMethod = PaymentFactory::getPaymentMethod($matches[2]);
      if (!isset($matches[3])) {
        $this->getPayment($paymentMethod, $order, $data);
      } else {
        $this->getPaymentReturn($paymentMethod, $order, $data);
      }
    } else {
      debuguear("Controller: 404 page");
    }
  }

  // POST/pedido {datos}
  public function postNewOrder(array $data): void
  {
    $order = new Order($data);
    debuguear("Controller: Created the order #{$order->id}.");
  }

  // OBTENER /pedidos
  public function getAllOrders(): void
  {
    debuguear("Controller: Here's all orders:");
    foreach (Order::get() as $order) {
      debuguear(json_encode($order, JSON_PRETTY_PRINT));
    }
  }

  // OBTENER /pedido/123/pago/XX
  public function getPayment(PaymentMethod $method, Order $order, array $data): void
  {
    // El trabajo real se delega al objeto del método de pago.
    $form = $method->getPaymentForm($order);
    debuguear("Controller: here's the payment form:");
    debuguear($form);
  }

  // GET /order/123/payment/XXX/return?key=AJHKSJHJ3423&success=true
  public function getPaymentReturn(PaymentMethod $method, Order $order, array $data): void
  {
    try {
      // Otro tipo de trabajo delegado al método de pago.
      if ($method->validateReturn($order, $data)) {
        debuguear("Controller: Thanks for your order!");
        $order->complete();
      }
    } catch (\Exception $e) {
      debuguear("Controller: got an exception (" . $e->getMessage() . ")");
    }
  }
}

// Una representación simplificada de la clase Order.
class Order
{
  // Para simplificar, almacenaremos todos los pedidos creados aquí...
  private static $orders = [];
  public $id;
  public $status;
  public $email;
  public $product;
  public $total;
  // ... Y acceder a ellos desde aquí.
  public static function get(int|null $orderId = null)
  {
    if ($orderId === null) {
      return static::$orders;
    } else {
      return static::$orders[$orderId];
    }
  }

  /* El constructor de pedidos asigna los valores de los campos del pedido. 
     Para simplificar, no hay validación alguna. 
  */
  public function __construct(array $attributes)
  {
    $this->id = count(static::$orders);
    $this->status = "new";
    foreach ($attributes as $key => $value) {
      $this->{$key} = $value;
    }
    static::$orders[$this->id] = $this;
    debuguear($this);
    debuguear(static::$orders);
  }

  // El método a llamar cuando se paga un pedido.
  public function complete(): void
  {
    $this->status = "completed";
    debuguear("Order: #{$this->id} is now {$this->status}.");
  }
}

/* Esta clase ayuda a producir un objeto de estrategia adecuado para 
   gestionar un pago.
*/
class PaymentFactory
{
  // Obtenga un método de pago por su ID.
  public static function getPaymentMethod(string $id): PaymentMethod
  {
    switch ($id) {
      case "cc":
        return new CreditCardPayment();
      case "paypal":
        return new PayPalPayment();
      default:
        throw new \Exception("Unknown Payment Method");
    }
  }
}

/* La interfaz de Estrategia describe cómo un cliente puede usar diversas 
   Estrategias Concretas.
   Tenga en cuenta que, en la mayoría de los ejemplos que puede encontrar en 
   la web, las estrategias suelen realizar alguna pequeña acción dentro de un 
   solo método. Sin embargo, en realidad, sus estrategias pueden ser mucho 
   más robustas (al contar con varios métodos, por ejemplo). 
*/
interface PaymentMethod
{
  public function getPaymentForm(Order $order): string;

  public function validateReturn(Order $order, array $data): bool;
}

/* Esta estrategia concreta proporciona un formulario de pago y valida las 
   devoluciones para pagos con tarjeta de crédito.
*/
class CreditCardPayment implements PaymentMethod
{
  static private $store_secret_key = "swordfish";

  public function getPaymentForm(Order $order): string
  {
    $returnURL = "https://our-website.com/" .
      "order/{$order->id}/payment/cc/return";

    return <<<FORM
    <form action="https://my-credit-card-processor.com/charge" method="POST">
        <input type="hidden" id="email" value="{$order->email}">
        <input type="hidden" id="total" value="{$order->total}">
        <input type="hidden" id="returnURL" value="$returnURL">
        <input type="text" id="cardholder-name">
        <input type="text" id="credit-card">
        <input type="text" id="expiration-date">
        <input type="text" id="ccv-number">
        <input type="submit" value="Pay">
    </form>
    FORM;
  }

  public function validateReturn(Order $order, array $data): bool
  {
    debuguear("CreditCardPayment: ...validating... ");

    if ($data['key'] != md5($order->id . static::$store_secret_key)) {
      throw new \Exception("Payment key is wrong.");
    }

    if (!isset($data['success']) || !$data['success'] || $data['success'] == 'false') {
      throw new \Exception("Payment failed.");
    }

    if (floatval($data['total']) < $order->total) {
      throw new \Exception("Payment amount is wrong.");
    }

    debuguear("Done!");

    return true;
  }
}

/* Esta estrategia concreta proporciona un formulario de pago y valida las 
   devoluciones para pagos con PayPal.
*/
class PayPalPayment implements PaymentMethod
{
  public function getPaymentForm(Order $order): string
  {
    $returnURL = "https://our-website.com/" .
      "order/{$order->id}/payment/paypal/return";

    return <<<FORM
      <form action="https://paypal.com/payment" method="POST">
          <input type="hidden" id="email" value="{$order->email}">
          <input type="hidden" id="total" value="{$order->total}">
          <input type="hidden" id="returnURL" value="$returnURL">
          <input type="submit" value="Pay on PayPal">
      </form>
    FORM;
  }

  public function validateReturn(Order $order, array $data): bool
  {
    debuguear("PayPalPayment: ...validating... ");

    debuguear("Done!");

    return true;
  }
}

// El código de cliente.

$controller = new OrderController();

debuguear("Client: Let's create some orders");

// creacion de productos
$controller->post("/orders", [
  "email" => "me@example.com",
  "product" => "ABC Cat food (XL)",
  "total" => 9.95,
]);


$controller->post("/orders", [
  "email" => "me@example.com",
  "product" => "XYZ Cat litter (XXL)",
  "total" => 19.95,
]);



debuguear(" ----------- Client: List my orders, please -----------");


$controller->get("/orders");



debuguear(" ----------- Client: I'd like to pay for the second, show me the payment form -----------");



$controller->get("/order/1/payment/paypal");



debuguear("Client: ...pushes the Pay button...");
debuguear("Client: Oh, I'm redirected to the PayPal.");
debuguear("Client: ...pays on the PayPal...");
debuguear("Client: Alright, I'm back with you, guys.");



$controller->get("/order/1/payment/paypal/return" .
  "?key=c55a3964833a4b0fa4469ea94a057152&success=true&total=19.95");
