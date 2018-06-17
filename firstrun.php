<?php
  
  

  function run_process( $cmd){
    $descriptorspec = array(
        0 => array("pipe", "r"), 
        1 => array("pipe", "w"), 
        2 => array("pipe", "r")
    );
    $process = proc_open($cmd, $descriptorspec, $pipes, null, null); //run test_gen.php
    echo ("Start process:\n");
    if (is_resource($process)) 
    {
        fwrite($pipes[0], "start\n");    // send start
        echo ("\n\nStart ....".fgets($pipes[1],4096)); //get answer
        fwrite($pipes[0], "get\n");    // send get
        echo ("Get: ".fgets($pipes[1],4096));    //get answer
        fwrite($pipes[0], "stop\n");    //send stop
        echo ("\n\nStop ....".fgets($pipes[1],4096));  //get answer
    
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $return_value = proc_close($process);  //stop test_gen.php
        echo ("Returned:".$return_value."\n");
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