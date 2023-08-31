<?php 
    session_start(); ob_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require(dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "GTAPinas-Site" . DIRECTORY_SEPARATOR . "inc/config.php");
    require(DIR_BASE . "inc/db.php");
    require(DIR_BASE . "inc/header.php");
    require(DIR_BASE . "inc/functions.php");

    if(!isset($_GET['page'])) 
    {
        header("Location: ?page=home");
    }

    $page = isset($_GET['page']) 
    ? preg_replace("/\W+/", "", $_GET['page']) : DIR_VIEWS . "home";
    include DIR_VIEWS . "$page.php";
?>