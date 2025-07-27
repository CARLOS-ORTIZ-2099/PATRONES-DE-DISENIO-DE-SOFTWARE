<?php
// interface que define comandos genericos
interface CommandDevices
{
  public function execute(): void;
  public function undo(): void;
}

// comandos concretos que implementan de la interface

class TurnOnCommand implements CommandDevices
{
  protected DevicesElectronic $receptor;
  public function __construct(DevicesElectronic $receptor)
  {
    $this->receptor = $receptor;
  }
  public function execute(): void
  {
    $this->receptor->turnOn();
  }
  public function undo(): void
  {
    $this->receptor->turnOff();
  }
}

class TurnOffCommand implements CommandDevices
{
  protected DevicesElectronic $receptor;
  public function __construct(DevicesElectronic $receptor)
  {
    $this->receptor = $receptor;
  }
  public function execute(): void
  {
    $this->receptor->turnOff();
  }
  public function undo(): void
  {
    $this->receptor->turnOn();
  }
}


// creando el/los receptores a los que se les vinculara los comandos

abstract class DevicesElectronic
{
  protected $name;
  public function __construct($name)
  {
    $this->name = $name;
  }
  public function turnOn(): void
  {
    debuguear("encendiendo el dispositivo " . $this->name);
  }
  public function turnOff(): void
  {
    debuguear("apagando el dispositivo " . $this->name);
  }
}

class Playstation extends DevicesElectronic {}

class PcGamer extends DevicesElectronic {}


// creando el invocador

class RemoteControl
{
  protected CommandDevices $command;
  protected array $history = [];

  public function __construct(CommandDevices $command)
  {
    $this->setCommand($command);
  }

  public function setCommand(CommandDevices $command)
  {
    $this->command = $command;
  }

  public function execute()
  {
    $this->command->execute();
    $this->history[] = $this->command;
  }

  public function undo()
  {
    $last = array_pop($this->history);
    if ($last) {
      $last->undo();
    }
  }
}

$psOn = new TurnOnCommand(new Playstation("PS4"));
$psOf = new TurnOffCommand(new Playstation("PS4"));
$pcOn = new TurnOnCommand(new PcGamer("pc ryzen 7"));
$pcOf = new TurnOffCommand(new PcGamer("pc ryzen 7"));

$remoteControl = new RemoteControl($psOn);
$remoteControl->execute();
$remoteControl->setCommand($psOf);
$remoteControl->execute();

$remoteControl->setCommand($pcOn);
$remoteControl->execute();
$remoteControl->setCommand($pcOf);
$remoteControl->execute();
// debuguear($remoteControl);
debuguear(" -------------- revirtiendo cambios -------------- ");
$remoteControl->undo();
$remoteControl->undo();
$remoteControl->undo();
$remoteControl->undo();

debuguear($remoteControl);
