<?php 

require __DIR__ . '/../class/db.php';
require __DIR__ . '/../class/function.php';

$dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
$pdo = $dbClass->getConnection();
$obj = new ucpProject($pdo);

?>

<?php if (isset($_POST['houseid'])): ?>
    <?php $housedata = $obj->fetchHouseData($_POST['houseid']); ?>
    <?php $weapons = $obj->fetchHouseWeapons($_POST['houseid']); ?>

    <?php if(!empty($housedata)): ?>
        <?php foreach($housedata as $house): ?>
            <h5 class="text-center border-bottom">House Safe</h5>

            <table class="table table-hover">
                <tr>
                    <td><b>Money</b></td>
                    <td class="text-center">$<?php echo number_format($house['money']); ?></td>
                </tr>
            </table>

            <h5 class="text-center border-bottom">Weapons</h5>

            <?php if(!empty($weapons)): ?>
                <table class="table table-hover">
                    <?php foreach($weapons as $w): ?>
                        <tr>
                            <td><?php echo $obj->getWeaponName($w['weapon']); ?></td>
                            <td class="text-center"><b>Ammo:</b> <?php echo $w['ammo']; ?>
                        </tr>
                    <?php endforeach; ?>
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
