<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Home Page</title>

    <link rel="shortcut icon" href="./favicon.ico"/>

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

        <div class="row d-flex justify-content-center align-items-center h-50">
                    
            <div class="col-lg-5 col-xs-12">
                <h1 style="font-size: 80px; font-family: 'Roboto'; color: white;">
                    <?php echo SITE_NAME; ?>
                </h1>
                <h5 style="font-family: 'Roboto'; font-size: 24px; color: white;">Begin your journey with us</h5>

                <a href="https://gtapinas.xyz/discord" class="btn border text-white">Join Now!</a>
            </div>
            
        </div>

    </div>
</body>

<?php require(DIR_INC . 'footer.php') ?>

</html>