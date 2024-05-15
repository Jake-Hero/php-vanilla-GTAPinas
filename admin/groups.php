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

    $page = 'groups.php';
    $page_name = "Groups";

    $gangs = $obj->getGangs();
    $factions = $obj->getFactions();
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

            <h3>Gangs</h3>

            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Strikes</th>
                            <th>Wealth</th>
                            <th>Members</th>
                            <th>Leader</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($gangs)): ?>
                        <?php foreach($gangs as $g): ?>
                        <?php $leader = $obj->fetchData('characters', 'charname', 'id', $g['leader']); ?>
                        <?php $members = $obj->countRowsInTableEx('characters', 'gang', $g['id']); ?>

                        <tr>
                            <td><?php echo $g['name']; ?></td>
                            <td><?php echo $g['strike']; ?></td>
                            <td>$<?php echo number_format($g['cash']); ?></td>
                            <td><?php echo $members; ?></td>
                            <td><?php echo $leader; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="5">No gang created in the server yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <hr />

            <h3>Factions</h3>

            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Average Salary</th>
                            <th>Members</th>
                            <th>Leader</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($factions)): ?>
                        <?php foreach($factions as $f): ?>
                        <?php 
                            $salary = [];
                            for($i = 0; $i < 15; $i++) {
                                $key = 'salary_' . $i;
                                $salary[] = $f[$key];
                            }
                        ?>

                        <?php $leader = $obj->fetchData('characters', 'charname', 'id', $f['leader']); ?>
                        <?php $members = $obj->countRowsInTableEx('characters', 'faction', $f['factionid']); ?>
                        <?php $avg = $obj->computeAverage($salary); ?>

                        <tr>
                            <td><?php echo $f['name']; ?></td>
                            <td>$<?php echo number_format($avg); ?></td>
                            <td><?php echo $members; ?></td>
                            <td><?php echo $leader; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="3">No faction created in the server yet.</td>
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