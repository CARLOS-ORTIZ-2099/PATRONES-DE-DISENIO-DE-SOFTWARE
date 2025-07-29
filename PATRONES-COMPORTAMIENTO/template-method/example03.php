<?php

abstract class Pokemon
{
  protected $name;
  protected $power;
  protected $attack;
  protected $defense;
  public function __construct($pokemon)
  {
    $this->name = $pokemon["name"];
    $this->power = $pokemon["power"];
    $this->attack = $pokemon["attack"];
    $this->defense = $pokemon["defense"];
  }
  // método plantilla
  public function calculateDamage()
  {
    $multipliers = $this->calculateMultiplier();
    $damage = $this->calculateImpact($multipliers);
    $this->showDamage($damage);
  }

  protected function calculateMultiplier()
  {
    return (1 / 2) * $this->power * random_int(1, 10);
  }

  protected function showDamage($damage)
  {
    debuguear("pokemon " . $this->name . " damage is :" . $damage);
  }

  abstract protected function calculateImpact($multipliers);
}



class FightingPokemon extends Pokemon
{
  protected function calculateImpact($multipliers)
  {
    return floor(($this->attack / $this->defense) * $multipliers) + 1;
  }
}

class PoisonPokemon extends Pokemon
{
  protected function calculateImpact($multipliers)
  {
    return floor(($this->attack - $this->defense) * $multipliers) + 1;
  }
}

class GroundPokemon extends Pokemon
{
  protected function calculateImpact($multipliers)
  {
    return floor(($this->attack + $this->defense) * $multipliers) + 1;
  }
}



$passimian = new FightingPokemon([
  "name" => "Passimian",
  "attack" => 10,
  "power" => 10,
  "defense" => 10
]);

$passimian->calculateDamage();



$poipole = new PoisonPokemon([
  "name" => "Poipole",
  "attack" => 10,
  "power" => 10,
  "defense" => 10
]);

$poipole->calculateDamage();
