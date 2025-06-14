<?php


class Teams
{
  public $teams = [];

  public function __construct($teams)
  {
    foreach ($teams as $team) {
      $this->teams[] = $team;
    }
  }
}

// prototipo
class Player
{
  protected string $name;
  protected int $age;
  protected string $country;
  protected string $position;
  public Teams $previousTeams;


  public function __construct(string $name, int $age, string $country, string $position, Teams $previousTeams)
  {

    $this->name = $name;
    $this->age = $age;
    $this->country = $country;
    $this->position = $position;
    $this->previousTeams = $previousTeams;
  }

  public function __clone()
  {
    debuguear('clonación exitosa');
    $this->previousTeams = clone $this->previousTeams;
  }

  public function getValue($key)
  {
    return $this->$key;
  }

  public function setValue($key, $value)
  {
    if (property_exists($this, $key)) {
      $this->$key = $value;
    }
  }
}


// prototipo

$teamsDinamic = ['manchester united', 'manchester city', 'napoly', 'inter', 'atletico madrid'];

$teams =  new Teams($teamsDinamic);


$p1 = new Player('cristiando ronaldo', 40, 'portugal', 'delantero', $teams);


// primer clon
$p2 = clone $p1;


$p2->setValue('name', 'nuno mendez');
$p2->setValue('age', 22);
$p2->setValue('position', 'defensa');
$p2->previousTeams->teams = ['psg'];

/* debuguear($p1);
debuguear($p2); 
*/

// segundo clon

$p3 = clone $p1;
$p3->setValue('name', 'dembele');
$p3->setValue('age', 28);
$p3->setValue('position', 'delantero');
$p3->setValue('country', 'francia');
$p3->previousTeams->teams = ['dortmund', 'barcelona', 'psg'];
debuguear($p1);
debuguear($p2);
debuguear($p3);
