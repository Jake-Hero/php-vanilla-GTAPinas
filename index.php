<?php 
    require __DIR__ . '/autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/header.php";

    /*if($obj->isLoggedIn() == true) {
        header("Location: " . SITE_URL . "/user/dashboard.php");
        die;
    }*/

    /*require(dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . "GTAPinas-Site" . DIRECTORY_SEPARATOR . "inc/config.php");

    if(!isset($_GET['page'])) 
    {
        header("Location: ?page=home");
    }

    $page = isset($_GET['page']) 
    ? preg_replace("/\W+/", "", $_GET['page']) : DIR_VIEWS . "home";
    if (!file_exists(DIR_VIEWS . "$page.php")) {
        header("Location: ?page=home");
    }
    else {
        include DIR_VIEWS . "$page.php";
    }*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="./"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Home Page</title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-autos">
        <div class="container-fluid">

            <div class="row d-flex justify-content-center align-items-center h-50">
                        
                <div class="col-lg-5 col-xs-12">
                <center><h1 style="font-size: 80px; color: white;">
                        <?php echo SITE_NAME; ?>
                    </h1></center>
                    <center><h5 style="font-size: 24px; color: white;">Begin your journey with us</h5></center>

                    <center><br><a href="https://gtapinas.xyz/discord" class="btn border text-white" style="width: 100%;">Join Now!</a></center>


                </div>
                
            </div>

        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>
</body>

</html>