<?php
require_once(__DIR__ . '/vendor/autoload.php'); //Composer
date_default_timezone_set('Europe/London');

$conn = new mysqli(getenv('DB_HOSTNAME'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'),getenv('DB_DATABASE'));
if ($conn->connect_error) {
	try {
		header('Location: https://error.jbithell.com/errors/database.html');
	} catch(Exception $e) {
 	 die('Error - <a href="http://error.jbithell.com/errors/database.html">click here</a> if not redirected   <meta http-equiv="refresh" content="0; url=https://error.jbithell.com/errors/database.html" />');
	}
} //die("Sorry - We are having trouble connecting to the database - Please try again later1");


$GLOBALS['CONN'] = $conn;
$DBLIB = new MysqliDb ($conn);
?>
