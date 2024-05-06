<?php 
    require __DIR__ . '/../autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/../header.php";

    if($obj->isLoggedIn() == false) {
        header("Location: " . SITE_URL . "/index.php");
        die;
    }

    $cid = $_GET['id'];
    $user = $obj->getCharacterData($cid);

    // if $user is null or
    // if no IDs were specified = redirect to dashboard.
    if($user == null || !isset($cid)) {
        header("Location:" . SITE_URL . "/user/dashboard.php");
        die;
    }   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME . ' - ' . $user['charname'] ?></title>

    <link rel="shortcut icon" href="./favicon.ico"/>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->
            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <h1 class="text-center mb-4 mt-3"><?php echo $user['charname']; ?></h1>
            <!-- Emulate Card Ends here -->
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <div class="container">
                <!-- Initiate container -->
                    <div class="row d-flex justify-content-center">
                    <!-- Initialize row -->
                        <div class="col text-center">
                            <img src="<?php echo $obj->getSkin($user['last_skin']); ?>" alt="<?php echo $user['charname'] ?>'s skin" height="300" />
                        </div>

                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <b>ID:</b> <?php echo $user['id']; ?><br/>
                                    <b>Level:</b> <?php echo $user['level']; ?><br/>
                                    <b>EXP Points:</b> <?php echo $user['exp']; ?><br/>
                                    <b>Time Played:</b> <?php echo $user['hours']; ?><br/>
                                </div>
                            </div>

                            <br/>

                            <div class="row">
                                <div class="col">
                                    <b>Creation:</b> <?php echo date('M d, Y H:i:s', $user['creation']); ?><br/>
                                    <b>Date of Birth:</b> <?php echo $user['birthday']; ?><br/>
                                    <b>Bank:</b> $<?php echo number_format($user['bank']); ?><br/>
                                    <b>Pocket Money:</b> $<?php echo number_format($user['cash']); ?><br/>
                                </div>
                            </div>
                        </div>
                    <!-- row ends here -->
                    </div>
                <!-- Container ends here -->
                </div>
            <!-- Emulate Card Ends here -->
            </div>
        <!-- Container Ends here -->
        </div>
    </div>

    <?php require DIR_INC . 'footer.php'; ?>
</body>
</html>