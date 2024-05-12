<?php 
    require __DIR__ . '/../autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/../header.php";

    $data = $obj->fetchPopularVehicles(20);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME . ' - ' . 'Popular Vehicles'; ?></title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <h1 class="text-center mb-4 mt-3">Top 20 Popular Vehicles</h1>
            <!-- Emulate Card Ends here -->
            </div>

            <div class='alert alert-warning'>
                <strong>Vehicles associated with Factions, Gangs, and Jobs are not included in the count.</strong> 
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th></th>
                                <th>Model</th>
                                <th>Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if(!empty($data)): ?>
                                <?php $i = 1; ?>
                                <?php foreach($data as $d): ?>
                                
                                <tr>
                                    <td class="align-middle"><?php echo $i; ?></td>
                                    <td><img src="<?php echo $obj->getVehicleImage($d['model']); ?>" alt="<?php echo $d['model'] ?>" /></td>
                                    <td class="align-middle"><?php echo $obj->getVehicleName($d['model']); ?></td>
                                    <td class="align-middle"><?php echo number_format($d['model_count']); ?></td>
                                </tr>

                                <?php $i ++; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <td colspan="4">There are no vehicles in the server.</td>
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