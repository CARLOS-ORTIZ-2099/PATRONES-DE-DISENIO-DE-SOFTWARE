<?php



// prototipo

abstract class  Shape
{
  protected int|null $x;
  protected int|null $y;
  protected String|null $color;


  public function __construct($target = null)
  {
    if ($target != null) {
      $this->x = $target->x ?? null;
      $this->y = $target->y ?? null;
      $this->color = $target->color ?? null;
    }
  }

  public abstract function clone(): Shape;
}


// figura circulo

class Circle extends Shape
{
  protected int|null $radius;


  public function __construct($target = null)
  {
    parent::__construct($target);
    if ($target != null) {
      $this->radius = $target->radius ?? null;
    }
  }


  public function clone(): Shape
  {
    return new Circle($this);
  }
}

// figura rectangúlo

class Rectangle extends Shape
{
  protected int|null $width;
  protected int|null $height;

  public function __construct($target = null)
  {
    parent::__construct($target);
    if ($target != null) {
      $this->width = $target->width ?? null;
      $this->height = $target->height ?? null;
    }
  }


  public function clone(): Shape
  {
    return new Rectangle($this);
  }
}



class Demo
{
  public static function  main(): void
  {
    $shapes = [];
    $shapesCopy = [];

    // circulos
    $dinamiCircle = new stdClass();
    $dinamiCircle->x = 10;
    $dinamiCircle->y = 15;
    $dinamiCircle->color = 'green';
    $dinamiCircle->radius = 25;

    $circle = new Circle($dinamiCircle);
    $circle2 = $circle->clone();
    $circle3 = $circle->clone();

    $shapes[] = $circle;
    $shapes[] = $circle2;
    $shapes[] = $circle3;

    // rectangulos
    $dinamiRectangle = new stdClass();
    $dinamiRectangle->color = 'blue';
    $dinamiRectangle->width = 150;
    $dinamiRectangle->height = 200;

    $rectangle = new Rectangle($dinamiRectangle);
    $rectangle2 = $rectangle->clone();

    $shapes[] = $rectangle;
    $shapes[] = $rectangle2;

    debuguear($shapes);
    self::cloneAndCompare($shapes, $shapesCopy);
  }

  private static function  cloneAndCompare(array $shapes, array $shapesCopy): void
  {

    foreach ($shapes as $shape) {
      $shapesCopy[] = $shape->clone();
    }
    debuguear($shapesCopy);

    for ($i = 0; $i < count($shapes); $i++) {
      if ($shapes[$i] !== $shapesCopy[$i]) {
        debuguear($i . ": Shapes are different objects (yay!)");
      } else {
        debuguear($i . ": Shape objects are the same (booo!)");
      }
    }
  }
}

Demo::main();
