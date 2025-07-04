<?php

/* La clase base Component declara una interfaz para todos los componentes 
   concretos, tanto simples como complejos.
   En nuestro ejemplo, nos centraremos en el comportamiento de renderizado 
   de los elementos DOM.
*/
abstract class FormElement
{
  // Podemos anticipar que todos los elementos DOM requieren estos 3 campos.
  protected $name;
  protected $title;
  protected $data;

  public function __construct(string $name, string $title)
  {
    $this->name = $name;
    $this->title = $title;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getData(): array
  {
    return $this->data;
  }

  public function setData($data): void
  {
    $this->data = $data;
  }

  /* Cada elemento DOM concreto debe proporcionar su implementación de 
     representación, pero podemos asumir con seguridad que todos devuelven 
     cadenas.
  */
  abstract public function render(): string;
}




// Este es un componente Hoja. Como todas las Hojas, no puede tener hijos.
class Input extends FormElement
{
  private $type;

  public function __construct(string $name, string $title, string $type)
  {
    parent::__construct($name, $title);
    $this->type = $type;
  }

  /* Dado que los componentes Leaf no tienen hijos que puedan encargarse de 
     la mayor parte del trabajo por ellos, generalmente son las hojas las 
     que hacen la mayor parte del trabajo pesado dentro del patrón Composite.
  */
  public function render(): string
  {
    return "<label for=\"{$this->name}\">{$this->title}</label>\n" .
      "<input name=\"{$this->name}\" type=\"{$this->type}\" value=\"{$this->data}\">\n";
  }
}

/* La clase base Composite implementa la infraestructura para administrar 
   objetos secundarios, utilizada por todos los Composites concretos.
*/
abstract class FieldComposite extends FormElement
{

  protected $fields = [];

  // Los métodos para agregar/eliminar subobjetos.
  public function add(FormElement $field): void
  {
    $name = $field->getName();
    $this->fields[$name] = $field;
  }

  public function remove(FormElement $component): void
  {
    $this->fields = array_filter($this->fields, function ($child) use ($component) {
      return $child != $component;
    });
  }

  /* Mientras que un método Leaf simplemente hace el trabajo, el método 
     Composite casi siempre tiene que tener en cuenta sus subobjetos. 
     En este caso, el compuesto puede aceptar datos estructurados.
  */
  public function setData($data): void
  {
    foreach ($this->fields as $name => $field) {
      if (isset($data[$name])) {
        $field->setData($data[$name]);
      }
    }
  }

  /* La misma lógica se aplica al getter. Este devuelve los datos 
     estructurados del propio compuesto (si lo hay) y todos los datos de los 
     elementos secundarios.
  */
  public function getData(): array
  {
    $data = [];

    foreach ($this->fields as $name => $field) {
      $data[$name] = $field->getData();
    }

    return $data;
  }

  /* La implementación base del renderizado del Composite simplemente 
     combina los resultados de todos los elementos secundarios. Los 
     Composites de Concreto podrán reutilizar esta implementación en sus 
     implementaciones de renderizado reales.
  */
  public function render(): string
  {
    $output = "";

    foreach ($this->fields as $name => $field) {
      $output .= $field->render();
    }

    return $output;
  }
}

// El elemento fieldset es un compuesto del concreto.
class Fieldset extends FieldComposite
{
  public function render(): string
  {
    /* Observe cómo el resultado de la representación combinada de los 
       elementos secundarios se incorpora en la etiqueta fieldset.  
    */
    $output = parent::render();

    return "<fieldset><legend>{$this->title}</legend>\n$output</fieldset>\n";
  }
}

// Y lo mismo ocurre con el elemento de forma.
class Form extends FieldComposite
{
  protected $url;

  public function __construct(string $name, string $title, string $url)
  {
    parent::__construct($name, $title);
    $this->url = $url;
  }

  public function render(): string
  {
    $output = parent::render();
    return "<form action=\"{$this->url}\">\n<h3>{$this->title}</h3>\n$output</form>\n";
  }
}

/* El código del cliente obtiene una interfaz conveniente para construir 
   estructuras de árbol complejas.
*/
function getProductForm(): FormElement
{
  $form = new Form('product', "Add product", "/product/add");
  $form->add(new Input('name', "Name", 'text'));
  $form->add(new Input('description', "Description", 'text'));
  // debuguear($form);

  $picture = new Fieldset('photo', "Product photo");
  $picture->add(new Input('caption', "Caption", 'text'));
  $picture->add(new Input('image', "Image", 'file'));
  // debuguear($picture);
  $form->add($picture);
  // debuguear($form, true);
  return $form;
}
// getProductForm();

/* La estructura del formulario puede contener datos de diversas fuentes. El 
   cliente no tiene que recorrer todos los campos del formulario para 
   asignar datos a varios campos, ya que el propio formulario puede 
   gestionarlo.
*/
function loadProductData(FormElement $form)
{
  $data = [
    'name' => 'Apple MacBook',
    'description' => 'A decent laptop.',
    'photo' => [
      'caption' => 'Front photo.',
      'image' => 'photo1.png',
    ],
  ];

  $form->setData($data);
}

/* El código del cliente puede trabajar con elementos de formulario mediante 
   la interfaz abstracta.
   De esta manera, no importa si el cliente trabaja con un componente simple
   o con un árbol compuesto complejo.
*/
function renderProduct(FormElement $form)
{
  echo $form->render();
}

$form = getProductForm();
// debuguear($form);
// loadProductData($form);
// debuguear($form);
renderProduct($form);
