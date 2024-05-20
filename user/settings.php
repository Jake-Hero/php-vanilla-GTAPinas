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

    $name = $obj->fetchData('accounts', 'username', 'id', $_SESSION['UID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../"/>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Settings</title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->
            <?php include_once __DIR__ . '/../time.php'; ?>

            <div class="row mt-2 mb-5">
                <!-- Back to My Characters -->
                <div class="col">
                    <a href="<?php echo SITE_URL; ?>/user/dashboard.php" class="btn btn-dark"><i class="fas fa-arrow-left"></i> Back to My Characters</a>
                </div>
            </div>


            <div class='alert alert-warning'>
                <strong>Settings in the user control panel will reflect into your in-game account.</strong> 
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <div class="container">
                    <h1 class="text-center mb-4 mt-3">Settings</h1>

                    <?php $disable = ($name == "test_account") ? "disabled" : "" ?>
                    <?php if($name == "test_account"): ?>

                    <div class='alert alert-warning'>
                        <strong>Settings feature is disabled on Demo Account.</strong> 
                    </div>

                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="col-lg-5 col-md-5 col-xs-12 float-none mx-auto">
                            <div id="ajax">
                                <?php 
                                    if(!empty($_SESSION['success_message']))
                                    {
                                        echo $_SESSION['success_message'];
                                        unset($_SESSION['success_message']);
                                    }
                                ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input class="form-control" type="password" placeholder="Password" name="cur_password" id="passForm" <?php echo $disable; ?> />
                            </div>

                            <div class="form-group">
                                <label class="form-label">Current Password</label>
                                <input class="form-control" type="password" placeholder="Current Password" name="cur_password" id="curpassForm" <?php echo $disable; ?> />
                            </div>

                            <div class="py-3 mt-3">
                                <button type="submit" class="btn btn-info w-100 text-white" onclick="return settingUser()">
                                    Save Settings
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            <!-- Emulate Card Ends here -->
            </div>
        <!-- Container Ends here -->
        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>/js/account.js" type="text/javascript"></script>
</body>
</html>