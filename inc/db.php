<?php
$host 			= 	'localhost';
$user 			= 	'root';
$password 		= 	'';
$database_name 	= 	'gta_pinas';

try
{
    $pdo = new PDO("mysql:host=$host;dbname=$database_name", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch (PDOException $e)
{
    echo '<strong>MySQL Error:</strong> ' . $e->getMessage();
	die();
}

?>