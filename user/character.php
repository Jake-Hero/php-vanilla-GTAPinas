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
        //header("Location:" . SITE_URL . "/user/dashboard.php");
        $obj->throw404();
    }   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME . ' - ' . $user['charname'] ?></title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->
            <div class="row mb-5">
                <!-- Back to My Characters -->
                <div class="col">
                    <a href="<?php echo SITE_URL; ?>/user/dashboard.php" class="btn btn-dark"><i class="fas fa-arrow-left"></i> Back to My Characters</a>
                </div>

                <div class="col d-flex justify-content-end">
                    <div class="row">
                        <!-- Settings -->
                        <div class="col">
                            <a href="#" class="btn btn-dark"><i class="fas fa-cog"></i> Settings</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($user['admin'] > 0): ?>
                <div class='alert alert-success'>
                    <strong>This character has administrator privileges in-game.</strong> 
                </div>
            <?php endif; ?>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <h1 class="text-center mb-4 mt-3"><?php echo $user['charname']; ?></h1>
            <!-- Emulate Card Ends here -->
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <div class="container mb-3">
                <!-- Initiate container -->
                    <div class="row d-flex justify-content-center">
                    <!-- Initialize row -->

                        <!-- Character's Skin -->
                        <div class="col text-center">
                            <img src="<?php echo $obj->getSkin($user['last_skin']); ?>" alt="<?php echo $user['charname'] ?>'s skin" height="300" />
                        </div>

                        <!-- Character's Info -->
                        <div class="col">
                            <div class="row py-4">
                                <div class="col">
                                    <b>ID:</b> <?php echo $user['id']; ?><br/>
                                    <b>Level:</b> <?php echo $user['level']; ?><br/>
                                    <b>EXP Points:</b> <?php echo $user['exp']; ?><br/>
                                    <b>Hour/s Played:</b> <?php echo number_format($user['hours']); ?> hour/s<br/>
                                    <b>Last Played:</b> <?php echo date('M d, Y H:i:s', $user['last_login']); ?><br/>
                                </div>
                            </div>

                            <br/>

                            <div class="row">
                                <div class="col">
                                    <b>Creation:</b> <?php echo date('M d, Y H:i:s', $user['creation']); ?><br/>
                                    <b>Date of Birth:</b> <?php echo $user['birthday'] . ' (<b>' . $obj->calculateCharacterAge($user['birthday']) . ' years old</b>)'; ?><br/>
                                    <b>Bank:</b> $<?php echo number_format($user['bank']); ?><br/>
                                    <b>Pocket Money:</b> $<?php echo number_format($user['cash']); ?><br/>
                                </div>
                            </div>
                        </div>
                    <!-- row ends here -->
                    </div>

                    <!-- Other Choices -->
                    <div class="shadow-lg p-3 mt-5 mb-5 bg-light rounded">
                        <div class="container mb-3">
                        <!-- Initiate container -->
                            <div class="row d-flex justify-content-center">
                            <!-- Initialize row -->
                            
                                <!-- Houses -->
                                <div class="col-xs-12 col-md-4 col-lg-3 col-xl-3 text-center" href="#">
                                    <a href="#" style="text-decoration: none; color: inherit;">
                                        <div>
                                            <i class="fas fa-home fa-10x" style="color: #33AA33"></i>
                                            <h1>Houses</h1>
                                        </div>
                                    </a>
                                </div>

                            <!-- row ends here -->
                            </div>
                        <!-- Container ends here -->
                        </div>
                    </div>

                <!-- Container ends here -->
                </div>
            <!-- Emulate Card Ends here -->
            </div>

        <!-- Container Ends here -->
        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>
</body>
</html>