<?php 
    require DIR_BASE . "inc/header.php";
    $obj->isLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" as="style" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title><?php echo SITE_NAME; ?> - Donation</title>
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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-4">
                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="./assets/donation-images/pink_dildo.jpg" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">Pink Dildo</h5>
                            <p class="card-text">Dildo toys are adult novelty items designed for sexual pleasure. Available in various shapes, sizes, and materials, they're commonly used for solo or couple's play and come in discreet packaging..</p>
                            <center><a href="#" class="btn btn-primary">Purchase</a></center>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="./assets/donation-images/gtapinas_logo.png" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <center><a href="#" class="btn btn-primary">Go somewhere</a></center>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="./assets/donation-images/gtapinas_logo.png" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <center><a href="#" class="btn btn-primary">Go somewhere</a></center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php require DIR_INC . 'footer.php'; ?>

</html>
