<?php
// Ensure we've initialized Composer
if (!file_exists(dirname(__DIR__) . '/vendor/autoload.php'))
{
	header('HTTP/1.1 500 Internal Server Error', null, 500);
	echo 'Composer is not set up properly, please run "composer install".';

	exit;
}

require dirname(__DIR__) . '/vendor/autoload.php';

// Execute the application
try
{
	(new BabDev\Application)->execute();
}
catch (\Exception $e)
{
	header('HTTP/1.1 500 Internal Server Error', null, 500);
	header('Content-Type: text/html; charset=utf-8');
	echo 'An error occurred while executing the application: ' . $e->getMessage();

	exit;
}
