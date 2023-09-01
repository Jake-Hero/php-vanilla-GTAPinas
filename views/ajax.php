<?php

if(isset($_GET['function']))
{
	if(!empty($_GET['function']))
	{
		switch($_GET['function'])
		{
			case 'userlogin': UserLogin(); break;
		}
	}
	else
	{
		header('Location: /?page=login');
	}
}
else
{
	header('Location: /?page=login');
}

?>