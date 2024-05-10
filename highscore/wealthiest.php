<?php 
    require __DIR__ . '/../autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/../header.php";

    $data = $obj->fetchWealthyUsers(20);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME . ' - ' . 'Wealthies Players'; ?></title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <h1 class="text-center mb-4 mt-3">Top 20 Wealthiest Players</h1>
            <!-- Emulate Card Ends here -->
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Total Wealth</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if(!empty($data)): ?>
                                <?php $i = 1; ?>
                                <?php foreach($data as $d): ?>
                                
                                <tr>
                                    <td class="align-middle"><?php echo $i; ?></td>
                                    <td class="align-middle"><?php echo $d['charname']; ?></td>
                                    <td class="align-middle">$<?php echo number_format($d['total_wealth']); ?></td>
                                </tr>

                                <?php $i ++; ?>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <td colspan="3">There are no user accounts registered in the server.</td>
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