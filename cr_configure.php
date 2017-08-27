<?php

/**
	Post installation configuration script for coldreader
*/

function execute($cmd){
	echo 'Executing '.$cmd.PHP_EOL;
	echo(shell_exec($cmd));
}

function ask_user($prompt){
	echo PHP_EOL.$prompt.' ';
	$input = trim(fgets(STDIN));
	return $input;
}

function replace_a_line($input_array = ['haystack', 'needle', 'newline']) {
   if (stristr($input_array[0], $input_array[1])) {
     return $input_array[2];
   }
   return $input_array[0];
}

echo 'beginning coldreader post-installation script'.PHP_EOL;
$dbs = ask_user('What is your database server? [127.0.0.1]');
$database_server = (!empty($dbs)) ? $dbs : '127.0.0.1';
$database_name = ask_user('What is the name of the database?');
$database_user = ask_user('What is the database username?');
$database_password = ask_user('What is the database password?');
/*
	This is the default configuration in the default .env
	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=homestead
	DB_USERNAME=homestead
	DB_PASSWORD=secret

*/

echo 'reading config file'.PHP_EOL;
$environment = file('.env');
echo 'here\'s what your environment looks like'.PHP_EOL;
var_export($environment);
echo 'making replacements'.PHP_EOL;
foreach ($environment as $key => $value){
	if (!(strrpos($value, 'DB_HOST=127.0.0.1')==FALSE)){
		$environment[$key] = 'DB_HOST='.$database_server . PHP_EOL;
	}
	if (!(strrpos($value, 'DB_DATABASE=')==FALSE)){
		$environment[$key] = 'DB_DATABASE='.$database_name . PHP_EOL;
	}
	if (!(strrpos($value, 'DB_USERNAME=')==FALSE)){
		$environment[$key] = 'DB_USERNAME='.$database_user . PHP_EOL;
	}
	if (!(strrpos($value, 'DB_PASSWORD=')==FALSE)){
		$environment[$key] = 'DB_PASSWORD='.$database_password . PHP_EOL;
	}
}
$env = implode('', $environment);
echo 'writing file'.PHP_EOL;
file_put_contents('.env', $env);
echo 'file written'.PHP_EOL;
execute('composer update');
execute('php artisan vendor:publish');
execute('php artisan migrate');
execute('php artisan storage:link');
execute('php artisan cliuser:create');

echo 'completed post-installation script.  enjoy the software!';

exit();
?>
