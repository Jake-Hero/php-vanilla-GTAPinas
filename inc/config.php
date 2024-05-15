<?php 

// Root Directory Name
define('DIR_NAME',      'GTAPinas-Site');
// Root Path
define('DIR_ROOT',      __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
// Website Name
define('SITE_NAME',     'GTA Pinas Roleplay');
// for linking Images & CSS, JS purposes.
define('SITE_URL',      'http://' . $_SERVER['HTTP_HOST'] . '/' . DIR_NAME);
// Project's Developer
define('SITE_DEV',      'Cipher');

// Path for Views (obsolete-disabled 06/05/24)
define('DIR_VIEWS',     DIR_ROOT . 'views' . DIRECTORY_SEPARATOR);
// Path for CSS
define('DIR_CSS',       DIR_ROOT . 'css' . DIRECTORY_SEPARATOR);
// Path for Javascript
define('DIR_JS',        DIR_ROOT . 'js' . DIRECTORY_SEPARATOR);
// Path for Includes Files
define('DIR_INC',       DIR_ROOT . 'inc' . DIRECTORY_SEPARATOR);
// Path for Assets
define('DIR_ASSETS',    DIR_ROOT . 'assets' . DIRECTORY_SEPARATOR);
// Path for Pictures (obsolete)
define('DIR_PICTURES',  './assets/pictures/');

// timezone set for PHP date & time.
date_default_timezone_set("Asia/Manila");

?>

<!-- CSS -->
<link rel='stylesheet' href="<?php echo SITE_URL ?>/css/style.css">
<link href= "<?php echo SITE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js" rel="stylesheet" media="nope!" onload="this.media='all'">
<link href= "<?php echo SITE_URL ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="nope!" onload="this.media='all'">

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- JavaScript -->
<script src= "https://unpkg.com/@popperjs/core@2"></script>
<script src="<?php echo SITE_URL; ?>/assets/bootstrap/js/bootstrap.min.js"></script>

<!-- Font -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
