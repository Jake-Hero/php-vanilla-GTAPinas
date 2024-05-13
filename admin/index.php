<?php 
    require __DIR__ . '/../autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/../header.php";

    if(($obj->isLoggedIn() == false) || ($obj->isUSerAdmin() < 1)) {
        header("Location: " . SITE_URL . "/index.php");
        die;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Admin Panel</title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->
            <?php require_once __DIR__ . '/../admin_header.php'; ?>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <div class='alert alert-warning'>
                    Admin Panel's Dashboard is still under development.
                </div>

                <div class='alert alert-primary'>
                    <strong>Admin MOTD: <?php echo $obj->fetchConfigData('ADMIN_MOTD'); ?></strong> 
                </div>

                <div class="row">
                    <div class="col border-top border-end border-4 text-center">
                        <div style="margin: 50px">
                            <span class="fas fa-gavel" style="font-size: 50px;"></span>
                            <h4><?php echo number_format($obj->countRowsInTable('bans')); ?></h4>
                            <h3>Banned Players</h3>
                        </div>
                    </div>
                    <div class="col border-top border-end border-4 text-center">
                        <div style="margin: 50px">
                            <span class="fas fa-users" style="font-size: 50px; color: #FF0000;"></span>
                            <h4><?php echo number_format($obj->countRowsInTable('accounts')); ?></h4>                        
                            <h3>Registered Users</h3>
                        </div>
                    </div>
                    <div class="col border-top border-4 text-center">
                        <div style="margin: 50px">
                            <span class="fas fa-home" style="font-size: 50px; color: #33AA33;"></span>
                            <h4><?php echo number_format($obj->countRowsInTable('properties')); ?></h4>        
                            <h3>Properties</h3>
                        </div>
                    </div>
                </div>

            <!-- Emulate Card Ends here -->
            </div>

        <!-- Container Ends here -->
        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>
</body>
</html>