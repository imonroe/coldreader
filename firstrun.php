<?php
  
  

  function run_process( $cmd){
    $descriptors = array(
      array('file', '/dev/tty', 'r'),
      array('file', '/dev/tty', 'w'),
      array('file', '/dev/tty', 'w')
    );
    $process = proc_open($cmd, $descriptors, $pipes);
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