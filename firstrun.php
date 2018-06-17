<?php
  
  

  function run_process( $cmd){
    $descriptors = array(
      0 => array ("file", "php://stdin", "r"),
      1 => array ("file", "php://stdout", "w"),
      2 => array ("file", "php://stdout", "w")
    );
    $process = proc_open($cmd, $descriptors, $pipes);
    if (is_resource($process)) {
      echo stream_get_contents($pipes[1]);
      fclose($pipes[1]);
      $return_value = proc_close($process);
      echo "command returned $return_value\n";
    }
  }

  $msg = PHP_EOL.PHP_EOL;
  $msg .= 'Thanks for trying out Coldreader!'.PHP_EOL;
  echo($msg);
  if (!file_exists('./vendor/imonroe/ana/src/Ana.php')){
    echo('Looks like you installed via git. First we will get the composer dependencies.'.PHP_EOL.PHP_EOL);
    run_process('composer install --no-dev --no-suggest --no-scripts');
  }
  echo('Now we will run the setup script.'.PHP_EOL.PHP_EOL);
  run_process('artisan coldreader:install');
?>