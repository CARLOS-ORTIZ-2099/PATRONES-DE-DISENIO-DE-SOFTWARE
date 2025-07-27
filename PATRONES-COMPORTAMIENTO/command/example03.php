<?php


// Command interface
interface CommandInterface
{
  public function execute(): void;
}


/* Tanto TurnOnCommand como TurnOffCommand son comandos que    
   funcionan a nivel de dispositivo 
*/
// Concrete command for turning a device on
class TurnOnCommand implements CommandInterface
{
  private Device $device;

  public function __construct(Device $device)
  {
    $this->device = $device;
  }

  public function execute(): void
  {
    $this->device->turnOn();
  }
}

// Concrete command for turning a device off
class TurnOffCommand implements CommandInterface
{
  private Device $device;

  public function __construct(Device $device)
  {
    $this->device = $device;
  }

  public function execute(): void
  {
    $this->device->turnOff();
  }
}

/* Tanto AdjustVolumeCommand como ChangeChannelCommand son comandos que    
   funcionan a nivel de dispositivo concreto
*/

// Concrete command for adjusting the volume of a stereo
class AdjustVolumeCommand implements CommandInterface
{
  private Stereo $stereo;

  public function __construct(Stereo $stereo)
  {
    $this->stereo = $stereo;
  }

  public function execute(): void
  {
    $this->stereo->adjustVolume();
  }
}

// Concrete command for changing the channel of a TV
class ChangeChannelCommand implements CommandInterface
{
  private TV $tv;

  public function __construct(TV $tv)
  {
    $this->tv = $tv;
  }

  public function execute(): void
  {
    $this->tv->changeChannel();
  }
}

// Receiver interface
interface Device
{
  public function turnOn(): void;
  public function turnOff(): void;
}

// Concrete receiver for a TV
class TV implements Device
{

  public function turnOn(): void
  {
    debuguear("TV is now on");
  }

  public function turnOff(): void
  {
    debuguear("TV is now off");
  }

  public function changeChannel(): void
  {
    debuguear("Channel changed");
  }
}

// Concrete receiver for a stereo
class Stereo implements Device
{
  public function turnOn(): void
  {
    debuguear("Stereo is now on");
  }


  public function turnOff(): void
  {
    debuguear("Stereo is now off");
  }

  public function adjustVolume(): void
  {
    debuguear("Volume adjusted");
  }
}

// Invoker
class RemoteControl
{
  private CommandInterface $command;

  public function setCommand(CommandInterface $command): void
  {
    $this->command = $command;
  }

  public function pressButton(): void
  {
    $this->command->execute();
  }
}

// Example usage
class CommandPatternExample
{
  public static function main(): void
  {
    // Create devices(receptores)
    $tv = new TV();
    $stereo = new Stereo();

    // Create command objects
    $turnOnTVCommand = new TurnOnCommand($tv);
    $turnOffTVCommand = new TurnOffCommand($tv);
    $adjustVolumeStereoCommand = new AdjustVolumeCommand($stereo);
    $changeChannelTVCommand = new ChangeChannelCommand($tv);

    // Create remote control(invocador)
    $remote = new RemoteControl();

    // Set and execute commands
    $remote->setCommand($turnOnTVCommand);
    $remote->pressButton(); // Outputs: TV is now on

    $remote->setCommand($adjustVolumeStereoCommand);
    $remote->pressButton(); // Outputs: Volume adjusted

    $remote->setCommand($changeChannelTVCommand);
    $remote->pressButton(); // Outputs: Channel changed
    //--
    $remote->setCommand($turnOffTVCommand);
    $remote->pressButton(); // Outputs: TV is now off 
  }
}

CommandPatternExample::main();
