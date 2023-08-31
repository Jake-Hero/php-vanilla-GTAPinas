<?php 
    session_start(); ob_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require("./inc/config.php");
    require("./inc/header.php");

    if(!isset($_GET['page'])) 
    {
        header("Location: ?page=home");
    }

    $page = isset($_GET['page']) 
    ? preg_replace("/\W+/", "", $_GET['page']) : DIR_VIEWS . "home";
    include DIR_VIEWS . "$page.php";
?>