<?php 

require __DIR__ . '/../class/db.php';
require __DIR__ . '/../class/function.php';
require __DIR__ . '/../inc/config.php';

$dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
$pdo = $dbClass->getConnection();
$obj = new ucpProject($pdo);

$id = $_POST['vehid'];

?>

<?php if (isset($id)): ?>
    <?php $vehdata = $obj->fetchVehicleData($id); ?>
    <?php $weapons = $obj->fetchVehicleWeapons($id); ?>

    <?php if(!empty($vehdata)): ?>
        <?php foreach($vehdata as $v): ?>

            <h5 class="text-center border-bottom">Inventory</h5>

            <table class="table table-hover">
                <tbody>
                    <tr>
                        <td><b>Ticket</b></td>
                        <td class="text-center"><b>$</b><?php echo number_format($v['ticket']); ?></td>
                    </tr>
                </tbody>
            </table>

            <h5 class="text-center border-bottom">Weapons</h5>

            <?php if(!empty($weapons)): ?>
                <table class="table table-hover">
                    <tbody>
                        <?php foreach($weapons as $w): ?>
                            <tr>
                                <td><?php echo $obj->getWeaponName($w['weapon']); ?></td>
                                <td class="text-center"><b>Ammo:</b> <?php echo $w['ammo']; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <center>No weapon is stored in this house.</center>
            <?php endif; ?>

        <?php endforeach; ?>
    <?php else: ?>
        <?php die; ?>
    <?php endif; ?>

<?php endif; ?>

<?php die; ?>
