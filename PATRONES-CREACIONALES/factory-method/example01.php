<?php

/* el proposito de este patron es separar la lógica de creación del uso del objeto concreto, 
   de esta forma cumplimos con los principios de SRP, ya que cada uno tiene una responsabilidad 
   que son la creación y la usabilidad, tambien cumple con OCP ya que para crear un nuevo 
   producto no hace falta tocar el codigo de usabilidad solo creamos una nueva subclase que herede
   de la clase padre y sobreescribimos el metodo factory para que devuelve un objeto nuevo.
   Asi mismo tambien nos sirve para cuando no sabemos con que tipo de objetos exactamente 
   trabajara nuestro código. 
*/


// La implementación es la siguiente

/* - primero creamos una asbtracción con funcionalidades comunes y/o que tengan sentido para todos los
     objetos concretos (usualmente se les llama productos)
   
   - luego creamos los productos o clases concretas que implementen la abstracción anteriormente creada,
     cada una de ellas la implementara de forma distinta ejemplo una clase concreta carro y otra camión, 
     estos son vehiculos terrestres por lo cual tienen cualidades similares aunque pueden tener ciertas 
     diferencias, como el numero de asientos, la cantidad de peso que pueden soportar, el tamaño de los 
     neumaticos tipo de frenos, etc.

   - seguidamente creamos la clase creadora que esta va depender de la abstraccion que creamos anteriormente,
     sea cual sea la instancia que le pasemos estara bien siempre y cuando implemente de la misma, recalcar 
     que el metodo de creación puede ser abstracto esto con el fin de obligar a las subclases a implementarlo
     o pueden ser no abstractas de tal manera que el usuario puede sobreescribirla o no

   - finalmente las clases creadoras concretas, estas heredan de la clase creadora base, definen que tipo de
     objeto o producto se creara  
   
*/



/* La clase Creador declara el método de fábrica que debe devolver un objeto 
   de la clase Producto. Las subclases de Creador suelen proporcionar la 
   implementación de este método. 
*/

abstract class Creator
{
  /*Tenga en cuenta que el Creador también puede proporcionar alguna implemen-
    tación predeterminada del método de fábrica 
  */
  abstract public function factoryMethod(): Product;

  /*Tenga en cuenta también que, a pesar de su nombre, la responsabilidad principal
    del Creador no es crear productos. Normalmente, contiene lógica de negocio básica
    que se basa en objetos Producto, devueltos por el método de fábrica. Las subclases
    pueden modificar indirectamente esa lógica de negocio anulando el método de fábrica
    y devolviendo un tipo de producto diferente.
   */
  public function someOperation(): string
  {
    // Llame al método de fábrica para crear un objeto Producto.
    $product = $this->factoryMethod();
    // Ahora, utiliza el producto.
    $result = "Creador: El mismo código del creador acaba de funcionar con" .
      $product->operation();

    return $result;
  }
}



/* Los creadores concreto anulan el método de fábrica para cambiar el tipo de producto 
   resultante. 
*/

class ConcreteCreator1 extends Creator
{
  /*Tenga en cuenta que la firma del método aún utiliza el tipo de producto abstracto, 
    aunque el producto concreto se devuelve desde el método. De esta manera, el Creador 
    puede mantenerse independiente de las clases de producto concreto.
   */
  public function factoryMethod(): Product
  {
    return new ConcreteProduct1();
  }
}


class ConcreteCreator2 extends Creator
{
  public function factoryMethod(): Product
  {
    return new ConcreteProduct2();
  }
}

/* La interfaz del producto declara las operaciones que todos los productos concretos 
   deben implementar. 
*/

interface Product
{
  public function operation(): string;
}

/* Los productos concretos proporcionan varias implementaciones de la interfaz del
   producto. 
*/


class ConcreteProduct1 implements Product
{
  public function operation(): string
  {
    return "{Result of the ConcreteProduct1}";
  }
}

class ConcreteProduct2 implements Product
{
  public function operation(): string
  {
    return "{Result of the ConcreteProduct2}";
  }
}

/* El código del cliente funciona con una instancia de un creador concreto, aunque a 
   través de su interfaz base. Mientras el cliente siga trabajando con el creador a 
   través de la interfaz base, puede pasarle cualquier subclase del creador. 
*/


function clientCode(Creator $creator)
{

  debuguear("Cliente: No conozco la clase del creador, pero aún funciona.")
    . $creator->someOperation();
}

/* La aplicación elige el tipo de creador según la configuración o el entorno. */


debuguear("Aplicación: lanzada con ConcreteCreator1.");
clientCode(new ConcreteCreator1());



debuguear("Aplicación: lanzada con ConcreteCreator2.");
clientCode(new ConcreteCreator2());
