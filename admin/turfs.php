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

    $turfs = $obj->getTurfs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Groups</title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->
            <?php require_once __DIR__ . '/../admin_header.php'; ?>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->

            <h3>Turfs</h3>

            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Owner</th>
                            <th>Type</th>
                            <th>Availability</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($turfs)): ?>
                        <?php foreach($turfs as $t): ?>
                        <?php
                            $type = $obj->getTurfType($t['turftype']); 
                            $availiability = ($t['turfavailability'] > 0) ? ($t['turfavailability'] . ' hour(s)') : ('Available for Capture');
                            $leader = $obj->fetchData('characters', 'charname', 'id', $g['leader']);

                            $capturer = $t['turfcapturergroup']; 
                            $capturer_type = $t['turfcapturertype']; 
                            $owner = 'Neutral';

                            if($capturer_type > 0) {
                                $capturer = ($capturer_type == 1) ? ($obj->fetchData('factions', 'name', 'factionid', $capturer)) : ($obj->fetchData('gangs', 'name', 'id', $capturer));
                            }
                        ?>

                        <tr>
                            <td><?php echo $t['turfname']; ?></td>
                            <td><?php echo $type; ?></td>
                            <td><?php echo $capturer; ?></td>
                            <td><?php echo $availiability; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="4">No turfs created in the server yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
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