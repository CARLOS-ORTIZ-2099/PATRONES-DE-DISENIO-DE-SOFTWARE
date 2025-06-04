<?php

// Objeto final de creacion
class Hero
{
  public $armor;
  public $weapon;
  public $skills;

  // protected function __construct() {}

  public function toString() {}
}

// interface con metodos comunes para cada implementación
interface HeroBuilder
{
  public function setArmor($value);
  public function setWeapon($value);
  public function setSkills($value);
}



class HumanHeroBuilder implements HeroBuilder
{
  private Hero $hero;

  public function __construct()
  {
    $this->reset();
  }

  public function reset()
  {
    $this->hero = new Hero;
  }

  public function build()
  {
    $hero = $this->hero;
    $this->reset();
    return $hero;
  }

  public function setArmor($value)
  {
    $this->hero->armor = $value;
    return $this;
  }
  public function setWeapon($value)
  {
    $this->hero->weapon = $value;
    return $this;
  }
  public function setSkills($value)
  {
    $this->hero->skills = $value;
    return $this;
  }
}


class OrcHeroBuilder implements HeroBuilder
{

  private Hero $hero;

  public function __construct()
  {
    $this->reset();
  }

  public function reset()
  {
    $this->hero = new Hero;
  }

  public function build()
  {
    $hero = $this->hero;
    $this->reset();
    return $hero;
  }

  public function setArmor($value)
  {
    $this->hero->armor = $value;
    return $this;
  }
  public function setWeapon($value)
  {
    $this->hero->weapon = $value;
    return $this;
  }
  public function setSkills($value)
  {
    $this->hero->skills = $value;
    return $this;
  }
}


class Director
{
  private HeroBuilder $heroBuilder;

  public function setBuilder($value)
  {
    $this->heroBuilder = $value;
  }

  public function BuildHumanHeroBuilder()
  {
    $this->heroBuilder->setArmor('bronce')->setWeapon('espada')->setSkills('super velocidad');
  }
  public function BuildOrcHeroBuilder()
  {
    $this->heroBuilder->setArmor('cobre')->setWeapon('mazo')->setSkills('super fuerza');
  }
}


$director = new Director;
$human = new HumanHeroBuilder();
$orco = new OrcHeroBuilder();

// creación del humano
$director->setBuilder($human);
$director->BuildHumanHeroBuilder();
$human = $human->build();

// creación del orco
$director->setBuilder($orco);
$director->BuildOrcHeroBuilder();
$orco = $orco->build();

debuguear($human);
debuguear($orco);
