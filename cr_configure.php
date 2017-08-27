<?php

/**
	Post installation configuration script for coldreader
*/

function execute($cmd){
	echo 'Executing '.$cmd.PHP_EOL;
	echo(shell_exec($cmd));
}

echo 'beginning coldreader post-installation script'.PHP_EOL;

execute('composer update');
execute('php artisan migrate');
execute('php artisan vendor:publish');
execute('php artisan storage:link');
execute('php artisan vendor:publish');

echo 'completed post-installation script.  enjoy the software!';

exit();
