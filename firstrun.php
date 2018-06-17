<?php
  $msg = PHP_EOL.PHP_EOL;
  $msg .= 'Thanks for trying out Coldreader!'.PHP_EOL;
  echo($msg);
  if (!file_exists('./vendor/imonroe/ana/src/Ana.php')){
    echo('Looks like you installed via git. First we will get the composer dependencies.'.PHP_EOL.PHP_EOL);
    shell_exec('composer install --no-dev --no-suggest');
  }
  shell_exec('php artisan coldreader:install');
?>