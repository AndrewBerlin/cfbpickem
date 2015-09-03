<?php

DEFINE ('DB_USER', 'tonkaberlin');
DEFINE ('DB_PASSWORD', 'password');
DEFINE ('DB_HOST', 'mysql.cfbpickem.tk');
DEFINE ('DB_NAME', 'cfbpickem');

$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
OR die('Could not connect to MySQL ' .
		mysqli_connect_error());

?>
