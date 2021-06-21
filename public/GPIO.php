<?PHP
    
try{
  
  use PiPHP\GPIO\GPIO;
  use PiPHP\GPIO\Pin\PinInterface;
  
  require("../vendor/autoload.php");
  
  $pin_number = isset($_POST['pin_number']) ? $_POST['pin_number'] : null;
  
  $toggle = isset($_POST['toggle']) ? $_POST['toggle'] : null;

// Create a GPIO object
$gpio = new GPIO();

// Retrieve pin 18 and configure it as an output pin
$pin = $gpio->getOutputPin($pin_number);

if($toggle === 'on'){
  // Set the value of the pin high (turn it on)
  $pin->setValue(PinInterface::VALUE_HIGH);
}elseif($toggle === 'off'){
  // Set the value of the pin low (turn it off)
  $pin->setValue(PinInterface::VALUE_LOW);
}
  
} catch(Exception $e){
  echo $e->getMessage();
}
