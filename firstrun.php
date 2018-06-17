<?php
  
  

  function run_process( $cmd){
    $descriptors = array(
      0 => array('file', '/dev/tty', 'r'),  // stdin is a pipe that the child will read from
      1 => array('file', '/dev/tty', "w"),  // stdout is a pipe that the child will write to
      2 => array('file', '/dev/tty', "w") // stderr is a file to write to
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