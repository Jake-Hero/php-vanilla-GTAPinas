<?php
session_start(); ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Autoloader function
function autoloader($class) {
    // Define an array to map classes to their respective files
    $class_map = array(
        'DB' => 'class/db.php',
        'ucpProject' => 'class/function.php'
        // Add more mappings as needed for other pages and classes
    );

    // Check if the class exists in the mapping array
    if (array_key_exists($class, $class_map)) {
        // Include the file corresponding to the class
        require_once $class_map[$class];
    }

    require_once __DIR__ . '/inc/config.php';
}

// Register the autoloader function
spl_autoload_register('autoloader');
?>