<?php

class GameEventManager implements \SplSubject
{

  public $subscribersByEvent;

  public function __construct()
  {
    $this->subscribersByEvent = [];
  }

  public function attach(SplObserver $observer, $event = null): void
  {

    $this->subscribersByEvent[$event][] = $observer;
  }

  public function detach(SplObserver $observer, $event = null): void
  {
    foreach ($this->subscribersByEvent[$event] as $index => $value) {
      if ($value === $observer) {
        unset($this->subscribersByEvent[$event][$index]);
      }
    }
  }

  public function notify($event = null): void
  {
    $passiveSkills = $this->subscribersByEvent[$event];
    if (!$passiveSkills) {
      return;
    }
    foreach ($passiveSkills as $value) {
      $value->update($this);
    }
  }

  public function randomNumber()
  {
    return rand(1, 10);
  }
}


class Slowdown implements \SplObserver
{
  public $power = 10;
  public function update(SplSubject $subject): void
  {
    $this->power += $subject->randomNumber();
  }
}

class Rage implements \SplObserver
{
  public $power = 20;
  public function update(SplSubject $subject): void
  {
    $this->power += $subject->randomNumber();
  }
}

$gameManage = new GameEventManager;

$slowdown = new Slowdown;
$rage = new Rage;

$gameManage->attach($slowdown, "movement");
$gameManage->attach($rage, "healthChange");


/* debuguear($gameManage);
$gameManage->detach($rage, "healthChange"); */
debuguear($gameManage);
$gameManage->notify("healthChange");
debuguear($gameManage);
