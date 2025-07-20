<?php

// El contexto define la interfaz de interés para los clientes.
class Context
{
  /* El contexto mantiene una referencia a uno de los objetos de estrategia. 
     El contexto no conoce la clase concreta de una estrategia. Debería 
     funcionar con todas las estrategias a través de la interfaz de 
     estrategia.
  */
  private $strategy;

  /* Normalmente, el contexto acepta una estrategia a través del constructor, 
     pero también proporciona un definidor para cambiarla en tiempo de 
     ejecución.
  */
  public function __construct(Strategy $strategy)
  {
    $this->strategy = $strategy;
  }

  // Generalmente, el Contexto permite reemplazar un objeto Estrategia en tiempo de ejecución.
  public function setStrategy(Strategy $strategy)
  {
    $this->strategy = $strategy;
  }

  /* El Contexto delega parte del trabajo al objeto Estrategia en lugar de 
    implementar múltiples versiones del algoritmo por su cuenta.
  */
  public function doSomeBusinessLogic(): void
  {
    debuguear("Context: Sorting data using the strategy (not sure how it'll do it)");
    $result = $this->strategy->doAlgorithm(["a", "b", "c", "d", "e"]);
    debuguear(implode(",", $result));
  }
}


/* La interfaz Strategy declara operaciones comunes a todas las versiones 
   compatibles de algún algoritmo.
   El Contexto utiliza esta interfaz para llamar al algoritmo definido por 
   las Estrategias Concretas.
*/
interface Strategy
{
  public function doAlgorithm(array $data): array;
}

/* Las Estrategias Concretas implementan el algoritmo siguiendo la interfaz 
   de la Estrategia base. Esta interfaz las hace intercambiables en el 
   Contexto.
*/
class ConcreteStrategyA implements Strategy
{
  public function doAlgorithm(array $data): array
  {
    sort($data);

    return $data;
  }
}

class ConcreteStrategyB implements Strategy
{
  public function doAlgorithm(array $data): array
  {
    rsort($data);

    return $data;
  }
}


/* El código del cliente elige una estrategia concreta y la transmite al 
   contexto. El cliente debe conocer las diferencias entre las estrategias 
   para tomar la decisión correcta.
*/
$context = new Context(new ConcreteStrategyA());
debuguear("Client: Strategy is set to normal sorting.");
$context->doSomeBusinessLogic();

debuguear("Client: Strategy is set to reverse sorting.");
$context->setStrategy(new ConcreteStrategyB());
$context->doSomeBusinessLogic();
