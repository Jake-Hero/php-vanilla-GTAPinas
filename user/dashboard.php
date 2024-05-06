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

    $users = $obj->getCharacters($_SESSION["UID"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Dashboard</title>

    <link rel="shortcut icon" href="./favicon.ico"/>
</head>

<body>
    <div class="container-fluid vh-100">
        <div class="container">
        <!-- Container -->
            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <div class="card-body">
                <!-- Card Body -->
                    <h1 class="text-center mb-4 mt-3">My Characters</h1>

                    <div class="row d-flex justify-content-center">
                    <!-- List all characters -->
                        <?php foreach ($users as $user): ?>
                        <div class="col-xs-3 col-sm-2 col-md-3 col-lg-3 text-center">
                            <div class="shadow-lg p-3 mb-5 bg-body rounded">
                            <!-- Cast shadows -->
                                <!-- Make it clickable to user/character.php?id=['id'] -->
                                <a href="<?php echo SITE_URL; ?>/user/character.php?id=<?php echo $user['id']; ?>" style="text-decoration: none; color: inherit;">
                                    <div class="card-body bg-white">
                                        <img src="<?php echo $obj->getSkin($user['last_skin']); ?>" alt="<?php echo $user['charname'] ?>'s skin" height="300" />
                                        <h4><?php echo $user['charname']; ?></h4>
                                        <p>Last Seen: <?php echo date('m/d/Y H:i:s', $user['last_login']); ?></p>
                                    </div>
                                </a>
                                <!-- End of Clickable -->
                            </div>
                            <!-- Cast shadows ends here -->
                        </div>
                        <?php endforeach; ?>
                    <!-- List all characters Ends here-->
                    </div>
                <!-- Card Body ends here -->
                </div>
            <!-- Emulate Card Ends here -->
            </div>
        <!-- Container Ends here -->
        </div>
    </div>

    <?php require DIR_INC . 'footer.php'; ?>
</body>
</html>