<?php

/* La clase base Component declara operaciones comunes para objetos simples 
   y complejos de una composición.
*/
abstract class Component
{
  protected $parent;

  /* Opcionalmente, el componente base puede declarar una interfaz para
     configurar y acceder a un componente principal del componente en una 
     estructura de árbol. También puede proporcionar una implementación 
     predeterminada para estos métodos.
  */
  public function setParent(Component|null $parent)
  {
    $this->parent = $parent;
  }

  public function getParent(): Component
  {
    return $this->parent;
  }

  /* En algunos casos, sería beneficioso definir las operaciones de gestión 
     de componentes secundarios directamente en la clase base del componente. 
     De esta manera, no será necesario exponer ninguna clase de componente 
     concreta al código del cliente, ni siquiera durante el ensamblaje del 
     árbol de objetos. La desventaja es que estos métodos estarán vacíos para 
     los componentes de nivel hoja.
  */
  public function add(Component $component): void {}


  public function remove(Component $component): void {}


  /* Puedes proporcionar un método que permita que el código del cliente 
     determine si un componente puede tener hijos.
  */
  public function isComposite(): bool
  {
    return false;
  }


  /* El componente base puede implementar algún comportamiento predeterminado 
     o dejarlo en manos de clases concretas (declarando el método que 
     contiene el comportamiento como "abstracto").
  */
  abstract public function operation(): string;
}




/* La clase Hoja representa los objetos finales de una composición. Una hoja 
   no puede tener hijos.
   Normalmente, son los objetos Hoja los que realizan el trabajo real, 
   mientras que los objetos Compuestos solo delegan a sus subcomponentes.
*/

class Leaf extends Component
{
  public function operation(): string
  {
    return "Leaf";
  }
}



/* La clase Composite representa los componentes complejos que pueden tener   
   componentes secundarios.
   Normalmente, los objetos Composite delegan el trabajo a sus componentes 
   secundarios y luego suman el resultado.
*/
class Composite extends Component
{

  protected $children;

  public function __construct()
  {
    // creando un estructura especial que contendra objetos, donde cada
    // uno de ellos se guardara como un clave dentro de dicha estructura.
    $this->children = new SplObjectStorage();
  }

  /* Un objeto compuesto puede agregar o eliminar otros componentes (simples 
     o complejos) a su lista de hijos.
  */
  public function add(Component $component): void
  {
    $this->children->attach($component);
    $component->setParent($this);
  }

  public function remove(Component $component): void
  {
    $this->children->detach($component);
    $component->setParent(null);
  }

  public function isComposite(): bool
  {
    return true;
  }

  /* El Composite ejecuta su lógica principal de una manera particular. 
     Recorre recursivamente todos sus hijos, recopilando y sumando sus 
     resultados. Dado que los hijos del Composite pasan estas llamadas a sus 
     hijos, y así sucesivamente, se recorre todo el árbol de objetos como 
     resultado.
  */
  public function operation(): string
  {
    $results = [];
    foreach ($this->children as $child) {
      $results[] = $child->operation();
    }

    return "Branch(" . implode("+", $results) . ")";
  }
}



/* El código del cliente funciona con todos los componentes a través de la 
   interfaz base.
*/
function clientCode(Component $component)
{
  debuguear("RESULT: " . $component->operation());
}

/* De esta manera el código del cliente puede soportar los componentes de 
   hoja simples...
*/
$simple = new Leaf();
debuguear("-------Client: I've got a simple component:-------");
clientCode($simple);


/* ...así como los compuestos complejos.
*/
$tree = new Composite();

// anidando objetos en la rama 1
$branch1 = new Composite();
debuguear($branch1);
$test = new Leaf();
$branch1->add($test);
debuguear($test);
$branch1->add(new Leaf());
debuguear($branch1);

// anidando objetos en la rama 2
$branch2 = new Composite();
debuguear($branch2);
$branch2->add(new Leaf());
debuguear($branch2);

// anidando objetos en el arbol principal
$tree->add($branch1);
$tree->add($branch2);
debuguear($tree);
debuguear("-------Client: Now I've got a composite tree:-------");
clientCode($tree);





/* Gracias a que las operaciones de gestión de componentes secundarios se   
   declaran en la clase base del componente, el código del cliente puede 
   funcionar con cualquier componente, simple o complejo, sin depender de sus 
   clases concretas.
*/
function clientCode2(Component $component1, Component $component2)
{

  if ($component1->isComposite()) {
    $component1->add($component2);
  }
  debuguear("RESULT: " . $component1->operation());
}

debuguear("-------Client: I don't need to check the components classes even when managing the tree:-------");
clientCode2($tree, $simple);
