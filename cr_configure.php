<?php
/**
 * Coldreader installation configuration script.
 * TODO: Wrap this up in an artisan command instead of using it as a separate script.
 * 
 * Please excuse the wrapper functions; I'm forgetful.
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

function say($msg){
	echo($msg . PHP_EOL);
}

/**
 * Here's where the fun begins.
 * 
 */
echo 'Beginning Coldreader post-installation script'.PHP_EOL;
$dbs = ask_user('What is your database server? [127.0.0.1]');
$dbn = ask_user('What is the name of the database? [homestead]');
$dbu = ask_user('What is the database username? [homestead]');
$dbp = ask_user('What is the database password? [secret]');
$url = ask_user('What is the app url? [http://localhost]');
$do_npm = ask_user('Install npm dependencies? y/n [n]');
$npm = (!empty($do_npm)) ? $do_npm : 'n';
$database_server = (!empty($dbs)) ? $dbs : '127.0.0.1';
$database_name = (!empty($dbn)) ? $dbn : 'homestead';
$database_user = (!empty($dbu)) ? $dbu : 'homestead';
$database_password = (!empty($dbp)) ? $dbp : 'secret';
$app_url = (!empty($url)) ? $url : 'http://localhost';

say('Reading config file');
if ( !file_exists('.env') ){
	execute('cp .env.example .env');
}
$environment = file('.env');

say('Making replacements');
foreach ($environment as $key => $value){
	if (!(strrpos($value, 'DB_HOST=')===FALSE)){
		$environment[$key] = 'DB_HOST='.$database_server . PHP_EOL;
	}
	if (!(strrpos($value, 'DB_DATABASE=')===FALSE)){
		$environment[$key] = 'DB_DATABASE='.$database_name . PHP_EOL;
	}
	if (!(strrpos($value, 'DB_USERNAME=')===FALSE)){
		$environment[$key] = 'DB_USERNAME='.$database_user . PHP_EOL;
	}
	if (!(strrpos($value, 'DB_PASSWORD=')===FALSE)){
		$environment[$key] = 'DB_PASSWORD='.$database_password . PHP_EOL;
	}
	if (!(strrpos($value, 'APP_NAME=')===FALSE)){
		$environment[$key] = 'APP_NAME=Coldreader' . PHP_EOL;
	}
	if (!(strrpos($value, 'APP_URL=')===FALSE)){
		$environment[$key] = 'APP_URL='. $app_url . PHP_EOL;
	}
}
$env = implode('', $environment);
say('Writing file');
file_put_contents('.env', $env);
say('File written');

execute('composer install');

execute('php artisan key:generate');

// not needed, since we are committing the configs.
//execute('php artisan vendor:publish');

execute('php artisan migrate');
execute('php artisan storage:link');

if ($npm == 'y'){
	execute('npm install');
	execute('npm run production');
}

execute('php artisan cliuser:create');

say('Completed post-installation script.  Enjoy the software!');

exit();
?>
