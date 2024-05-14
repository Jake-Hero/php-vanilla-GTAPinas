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
    $email = (!empty($obj->fetchData('accounts', 'email', 'id', $_SESSION['UID']))) ? $obj->fetchData('accounts', 'email', 'id', $_SESSION['UID']) : ("Unset");
    $verified = ($obj->fetchData('accounts', 'verified', 'id', $_SESSION['UID'])) ? ("Verified") : ("Not Verified");
    $vip = $obj->fetchData('accounts', 'donator', 'id', $_SESSION['UID']);
    $viptime = $obj->fetchData('accounts', 'donatortime', 'id', $_SESSION['UID']);
    $vip_expiration = ($vip > 0) ? ('('.date('m/d/Y h:iA', $viptime).')') : ('');

    $highest_slot = 0;
    foreach ($users as $user) {
        if ($user['slot'] > $highest_slot) {
            $highest_slot = $user['slot'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Dashboard</title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->
            <div class="d-flex justify-content-end mb-5">
                <div class="row">
                    <!-- Settings -->
                    <div class="col">
                        <a href="<?php echo SITE_URL; ?>/user/settings.php" class="btn btn-dark"><i class="fas fa-cog"></i> Settings</a>
                    </div>
                </div>
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <h1 class="text-center border-bottom mb-4 mt-3">My Characters</h1>

                <div class="row d-flex justify-content-center">
                <!-- List all characters -->
                    <?php for ($i = 1; $i <= 3; $i++): ?>

                        <?php $character_exists = false; ?>

                        <?php foreach ($users as $user): ?>

                            <?php if ($user['slot'] == $i): ?>

                                <?php $character_exists = true; ?>

                                <div class="col-xs-3 col-sm-2 col-md-3 col-lg-3 text-center">
                                    <div class="shadow-lg p-3 mb-5 bg-body rounded">
                                    <!-- Cast shadows -->
                                        <!-- Make it clickable to user/character.php?id=['id'] -->
                                        <a href="<?php echo SITE_URL; ?>/user/character.php?id=<?php echo $user['id']; ?>" style="text-decoration: none; color: inherit;">
                                            <div class="card-body bg-white">
                                                <img src="<?php echo $obj->getSkinImage($user['last_skin']); ?>" alt="<?php echo $user['charname'] ?>'s skin" height="300" />
                                                <h4><?php echo $user['charname']; ?></h4>
                                                <p>
                                                    <b>Online Time:</b> <?php echo $obj->secondsToHMS($user['hours']); ?>
                                                    <p class="text-muted">ID: <?php echo $user['id'] ?></p>
                                                </p>
                                            </div>
                                        </a>
                                        <!-- End of Clickable -->
                                    </div>
                                    <!-- Cast shadows ends here -->
                                </div>
                                <?php break; ?>
                            
                            <?php endif; ?>

                        <?php endforeach; ?>

                        <!-- Display "Create" card for empty slots -->
                        <?php
                            if (!$character_exists) {
                                ?>
                                <div class="col-xs-3 col-sm-2 col-md-3 col-lg-3 text-center">
                                    <div class="shadow-lg p-3 mb-5 bg-body rounded">
                                        <a href="<?php echo SITE_URL; ?>/user/create_character.php?slot=<?php echo $i; ?>" style="text-decoration: none; color: inherit;">
                                            <div class="card-body bg-white">
                                                <!--img src="<?php echo SITE_URL; ?>/assets/pictures/adrian.jpg" alt="Create Character" height="325" width="200" /-->
                                                <img src="<?php echo SITE_URL; ?>/assets/pictures/skins/undefined.png" alt="Create Character" height="340" />

                                                <h4>Create Character</h4>
                                                <p><b>Slot <?php echo $i; ?></b></p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                    <?php endfor; ?>
                <!-- List all characters Ends here-->
                </div>
            <!-- Emulate Card Ends here -->
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <h1 class="text-center border-bottom mb-4 mt-3">Account Information</h1>

                <div class="container">
                    <table class="table text-center">
                        <tr>
                            <td><b>Master Account Name</b></td>
                            <td><?php echo $obj->fetchData('accounts', 'username', 'id', $_SESSION['UID']); ?></td>
                        </tr>

                        <tr>
                            <td><b>Registration Date</b></td>
                            <td><?php echo date('F d, Y h:iA', $obj->fetchData('accounts', 'registerdate', 'id', $_SESSION['UID'])); ?></td>
                        </tr>

                        <tr>
                            <td><b>Total Hours Online</b></td>
                            <td><?php echo number_format($obj->calculateTotalHours($_SESSION['UID'])); ?></td>
                        </tr>

                        <tr>
                            <td><b>Email</b></td>
                            <td><?php echo $email . ' (' . $verified . ')'; ?></td>
                        </tr>

                        <tr>
                            <td><b>Donator Status</b></td>
                            <td><?php echo $obj->getDonatorRank($vip) . ' ' . $vip_expiration; ?></td>
                        </tr>
                    </table>
                </div>
            <!-- Emulate Card Ends here -->
            </div>
        <!-- Container Ends here -->
        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>
</body>
</html>