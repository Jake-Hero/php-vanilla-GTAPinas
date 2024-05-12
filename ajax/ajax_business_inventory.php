<?php 

require __DIR__ . '/../class/db.php';
require __DIR__ . '/../class/function.php';
require __DIR__ . '/../inc/config.php';

$dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
$pdo = $dbClass->getConnection();
$obj = new ucpProject($pdo);

$bizid = $_POST['bizid'];

?>

<?php if (isset($bizid)): ?>
    <?php $bizdata = $obj->fetchBizData($bizid); ?>
    <?php $products = $obj->fetchBizProducts($bizid); ?>

    <?php if(!empty($bizdata)): ?>
        <?php foreach($bizdata as $biz): ?>

            <h5 class="text-center border-bottom">Inventory</h5>

            <table class="table table-hover">
                <tbody>
                    <tr>
                        <td class="text-left"><b>Products</b></td>
                        <td class="text-right"><?php echo number_format($biz['products']); ?></td>
                    </tr>
                </tbody>
            </table>

            <h5 class="text-center border-bottom">Products</h5>

            <?php if(!empty($products)): ?>
                <table class="table table-hover">

                    <tbody>
                        <?php foreach($products as $p): ?>
                            <tr>
                                <!-- Use getVehicleImage, if no images is detected from the value given, it will print out text instead. -->
                                <td class="text-left align-middle">
                                    <?php if($biz['type'] == 3): ?>
                                        <img src="<?php echo $obj->getVehicleImage($p['name']); ?>" alt="<?php echo $p['name'] ?>" />
                                    <?php else: ?>
                                        <?php echo $p['name']; ?>
                                    <?php endif; ?>
                                </td>
                                <?php if($biz['type'] == 3): ?>
                                <td class="align-middle"><?php echo $obj->getVehicleName($p['name']); ?></td>
                                <?php endif; ?>
                                <td class="text-right align-middle"><b>$</b><?php echo number_format($p['price']); ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else: ?>
                <center>No products are being sold to this business.</center>
            <?php endif; ?>

        <?php endforeach; ?>
    <?php else: ?>
        <?php die; ?>
    <?php endif; ?>

<?php endif; ?>

<?php die; ?>
