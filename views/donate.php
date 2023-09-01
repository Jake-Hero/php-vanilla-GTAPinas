<?php 
    require(DIR_BASE . "inc/header_checker.php");
    isLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Home Page</title>

    <style>
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            overflow: auto;

            position: relative;
            width: 100%;
            min-height: 100%;
            background-image: url(<?php echo DIR_PICTURES . 'background.png'; ?>);
            background-repeat: no-repeat;
            background-size: cover;
            display: block;
            background-attachment: fixed;
        }
    </style>
</head>

<body>
    <div class="container-fluid vh-100">

        <div class="card">
            <div class="card-body">
                
            </div>
        </div>

    </div>
</body>

<?php require(DIR_INC . 'footer.php') ?>

</html>