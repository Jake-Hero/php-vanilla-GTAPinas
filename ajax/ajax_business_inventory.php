<?php 

require __DIR__ . '/../class/db.php';
require __DIR__ . '/../class/function.php';

$dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
$pdo = $dbClass->getConnection();
$obj = new ucpProject($pdo);

?>

<?php if (isset($_POST['bizid'])): ?>
    <?php $bizdata = $obj->fetchBizData($_POST['bizid']); ?>
    <?php $products = $obj->fetchBizProducts($_POST['bizid']); ?>

    <?php if(!empty($bizdata)): ?>
        <?php foreach($bizdata as $biz): ?>

            <h5 class="text-center border-bottom">Inventory</h5>

            <table class="table table-hover">
                <tr>
                    <td><b>Products</b></td>
                    <td class="text-center"><?php echo number_format($biz['products']); ?></td>
                </tr>
            </table>

            <h5 class="text-center border-bottom">Products</h5>

            <?php if(!empty($products)): ?>
                <table class="table table-hover">
                    <thead>
                        <td>Product Name</td>
                        <td>Price</td>
                    </thead>

                    <?php foreach($products as $p): ?>
                        <tr>
                            <td class="text-left"><?php echo $p['name'] ?></td>
                            <td class="text-right"><b>$</b><?php echo number_format($p['price']); ?>
                        </tr>
                    <?php endforeach; ?>
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
