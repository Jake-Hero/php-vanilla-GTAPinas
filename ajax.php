<?php

require __DIR__ . '/autoload.php';

$dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
$pdo = $dbClass->getConnection();
$obj = new ucpProject($pdo);

if(isset($_GET['function']))
{
	if(!empty($_GET['function']))
	{
		switch($_GET['function'])
		{
			case 'userlogin': $obj->UserLogin(); break;
		}
	}
	else
	{
		header('Location: user/login.php');
	}
}
else
{
	header('Location: user/login.php');
}

?>