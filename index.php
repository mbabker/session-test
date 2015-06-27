<?php
$session_name = session_name();

$cookieData = &$_COOKIE;

if (isset($cookieData[$session_name]) && is_null($cookieData[$session_name]))
{
	$session_clean = isset($_REQUEST[$session_name]) ? filter_var($_REQUEST[$session_name], FILTER_SANITIZE_STRING) : null;

	if ($session_clean)
	{
		session_id($session_clean);
		setcookie($session_name, '', time() - 3600);
		unset($cookieData[$session_name]);
	}
}

session_register_shutdown();

session_cache_limiter('none');
session_start();

$counter = isset($_SESSION['counter']) ? $_SESSION['counter'] : 0;
++$counter;
$_SESSION['counter'] = $counter;

echo sprintf('The session has been hit %d times.', $counter);
