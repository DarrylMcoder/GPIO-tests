<?PHP

   load_classphp("../src");
    
  use PiPHP\GPIO\GPIO;
  use PiPHP\GPIO\Pin\PinInterface;
 
try{
  
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

function load_classphp($directory) {
    if(is_dir($directory)) {
        $scan = scandir($directory);
        unset($scan[0], $scan[1]); //unset . and ..
        foreach($scan as $file) {
            if(is_dir($directory."/".$file)) {
                load_classphp($directory."/".$file);
            } else {
                if(strpos($file, '.php') !== false) {
                    include_once($directory."/".$file);
                }
            }
        }
    }
}
