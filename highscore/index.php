<?php 
    require __DIR__ . '/../autoload.php';

    $pdo = null;
    $obj = new ucpProject($pdo);

    header("Location: " . SITE_URL . "/index.php");
    die;
?>