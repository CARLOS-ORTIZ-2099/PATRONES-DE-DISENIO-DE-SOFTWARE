<?php

// componente
// esta clase define  las caracteristicas minimas que debe tener un producto
// sin importar si son productos simples o compuestos
abstract class AbstractProduct
{
  protected string $name;
  protected float|int $price;

  public function __construct(string $name, float|int $price)
  {
    // super();
    $this->name = $name;
    $this->price = $price;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): void
  {
    $this->name = $name;
  }

  public function getPrice(): float|int
  {
    return $this->price;
  }

  public function setPrice(float|int $price): void
  {
    $this->price = $price;
  }
}


// componente simple o hoja
// aquí tambien podemos definir particularidades que puede tener un 
// determinado producto
class SimpleProduct extends AbstractProduct
{
  protected string $brand;

  public function __construct(string $name, float|int $price, string $brand)
  {
    //super(name, price);
    parent::__construct($name, $price);
    $this->brand = $brand;
  }

  public function getBrand(): string
  {
    return $this->brand;
  }

  public function setBrand(string $brand): void
  {
    $this->brand = $brand;
  }
}


// el componente compuesto, este es un objeto de tipo AbstractProduct y a su
// vez contiene objetos del tipo AbstractProduct, por lo tanto este objeto
// puede tener instancias de SimpleProduct(componentes simples) o 
// CompositeProduct(componentes compuestos)


class CompositeProduct extends AbstractProduct
{

  private $products = [];

  public function __construct(string $name)
  {
    parent::__construct($name, 0);
  }

  public function getPrice(): float|int
  {
    $price = 0;
    foreach ($this->products as $child) {
      $price += $child->getPrice();
    }

    return $price;
  }

  public function setPrice(float|int $price): void
  {
    throw new Exception("no se puede ejecutar esta operacion en un compuesto");
  }

  public function addProduct(AbstractProduct $product): void
  {
    $this->products[] = $product;
  }

  public function removeProduct(AbstractProduct $product): bool
  {
    //return this.products.remove(product);

    array_filter($this->products, function ($element) use ($product) {
      return $element != $product;
    });
    return true;
  }
}


// otra clase

class SaleOrder
{

  private  $orderId;
  private string $customer;
  private string $dateTime;
  private array $products = [];
  //private List< AbstractProduct > products = new ArrayList< >();

  public function __construct($orderId, string $customer)
  {
    // super();
    $this->orderId = $orderId;
    $this->customer = $customer;
  }

  public function getOrderId()
  {
    return $this->orderId;
  }

  public function setOrderId($orderId): void
  {
    $this->orderId = $orderId;
  }

  public function getCustomer(): string
  {
    return $this->customer;
  }

  public function setCustomer(string $customer): void
  {
    $this->customer = $customer;
  }

  public function getDateTime(): string
  {
    return $this->dateTime;
  }

  public function setDateTime(string $dateTime): void
  {
    $this->dateTime = $dateTime;
  }

  public function getProducts(): array
  {
    return $this->products;
  }

  public function setProducts(array $products): void
  {
    $this->products = $products;
  }

  public function getPrice(): float|int
  {
    $price = 0;
    foreach ($this->products as $child) {
      $price += $child->getPrice();
    }

    return $price;
  }

  public function addProduct(AbstractProduct $product): void
  {
    $this->products[] = $product;
  }

  public function removeProduct(AbstractProduct $product): void
  {
    //products.remove(product);
    array_filter($this->products, function ($element) use ($product) {
      return $element != $product;
    });
  }

  public function printOrder(): void
  {

    debuguear("============================================= \n Orden: " . $this->orderId . "\n Cliente: " . $this->customer . "\n Productos: \n");
    foreach ($this->products as $product) {
      debuguear($product->getName() . " $ " . $product->getPrice());
    }
    debuguear("Total: " . $this->getPrice() . "\n =============================================");
  }
}

// usando el composite
class Main
{

  public static function main(array $args = []): void
  {
    // creando componentes simples o hojas
    $ram4gb = new SimpleProduct("Memoria RAM 4GB", 750, "KingStone");
    $ram8gb = new SimpleProduct("Memoria RAM 8GB", 1000, "KingStone");

    $disk500gb = new SimpleProduct("Disco Duro 500GB", 1500, "ACME");
    $disk1tb = new SimpleProduct("Disco Duro 1TB", 2000, "ACME");

    $cpuAMD = new SimpleProduct("AMD phenon", 4000, "AMD");
    $cpuIntel = new SimpleProduct("Intel i7", 4500, "Intel");

    $smallCabinete = new SimpleProduct("Gabinete Pequeño", 2000, "ExCom");
    $bigCabinete = new SimpleProduct("Gabinete Grande", 2200, "ExCom");

    $monitor20inch = new SimpleProduct("Monitor 20'", 1500, "HP");
    $monitor30inch = new SimpleProduct("Monitor 30'", 2000, "HP");

    $simpleMouse = new SimpleProduct("Raton Simple", 150, "Genius");
    $gammerMouse = new SimpleProduct("Raton Gammer", 750, "Alien");
    // debuguear($ram4gb);



    // creando componentes compuestos 

    //Computadora para Gammer que incluye 8gb de ram,disco de 1tb, procesador Intel i7
    //gabinete grande,monitor de 30' y un mouse gammer.
    $gammerPC = new CompositeProduct("Gammer PC");
    $gammerPC->addProduct($ram8gb);
    $gammerPC->addProduct($disk1tb);
    $gammerPC->addProduct($cpuIntel);
    $gammerPC->addProduct($bigCabinete);
    $gammerPC->addProduct($monitor30inch);
    $gammerPC->addProduct($gammerMouse);
    // debuguear($gammerPC);


    //Computadora para Casa que incluye 4gb de ram,disco de 500gb, procesador AMD Phenon
    //gabinete chico,monitor de 20' y un mouse simple.
    $homePC = new CompositeProduct("Casa PC");
    $homePC->addProduct($ram4gb); //
    $homePC->addProduct($disk500gb);
    $homePC->addProduct($cpuAMD);
    $homePC->addProduct($smallCabinete);
    $homePC->addProduct($monitor20inch);
    $homePC->addProduct($simpleMouse);
    // debuguear($homePC);



    //Paquete compuesto de dos paquetes, El paquete Gammer PC y Home PC
    $pc2x1 = new CompositeProduct("Paquete PC Gammer + Casa");
    $pc2x1->addProduct($gammerPC);
    $pc2x1->addProduct($homePC);
    // debuguear($pc2x1);


    // generando ordenes

    //aqui vendemos un paquete
    $gammerOrder = new SaleOrder(1, "Juan Perez");
    $gammerOrder->addProduct($gammerPC);
    //debuguear($gammerOrder);
    $gammerOrder->printOrder();

    //aqui vendemos un paquete
    $homeOrder = new SaleOrder(2, "Marcos Guerra");
    $homeOrder->addProduct($homePC);
    //debuguear($homePC);
    $homeOrder->printOrder();

    // aqui vendemos un paquete conformado de dos paquetes 
    $comboOrder = new SaleOrder(3, "Paquete 2x1 en PC");
    $comboOrder->addProduct($pc2x1);
    //debuguear($comboOrder);
    $comboOrder->printOrder();


    // vendemos un paquete y 4 productos simples.
    $customOrder = new SaleOrder(4, "Oscar Blancarte");
    $customOrder->addProduct($homePC);
    $customOrder->addProduct($ram8gb);
    $customOrder->addProduct($ram4gb);
    $customOrder->addProduct($monitor30inch);
    $customOrder->addProduct($gammerMouse);
    //debuguear($customOrder);
    $customOrder->printOrder();
  }
}


Main::main();
