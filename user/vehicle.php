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

    // keep the variable '$cid' consitently named. So account_header.php can work properly.
    $cid = $_GET['id'];
    // fetch the character's vehicles
    $vehicle = $obj->fetchVehicles($cid);

    $user = $obj->getCharacterData($cid);
    $check_owner = $obj->isCharacterOwnedByUser($cid, $_SESSION['UID']);
    
    // if $user is null or
    // if no IDs were specified = throw 404 error.
    if(!$check_owner || empty($user) || !isset($cid)) {
        //header("Location:" . SITE_URL . "/user/dashboard.php");
        $obj->throw404();
    }

    $owner = $obj->fetchData('characters', 'charname', 'id', $cid);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../"/>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME . ' - ' . $owner ?>'s Vehicles</title>
</head>

<div class="modal fade" id="inventoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Inventory for Vehicle ID <span id="modalDataId"></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="ajax"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->
            <?php include_once __DIR__ . '/../account_header.php'; ?>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <h1 class="text-center mb-4 mt-3">
                    <?php echo $owner; ?>'s Vehicles
                </h1>
            <!-- Emulate Card Ends here -->
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <div class="container mb-3">
                <!-- Initiate container -->
                    
                    <?php if(!empty($vehicle)) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Model</th>
                                        <th>Locked</th>
                                        <th>Mileage</th>
                                        <th>Fuel</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php foreach($vehicle as $v): ?>
                                    <?php $locked = ($v['locked']) ? ("Locked") : ("Unlocked"); ?>

                                    <tr>
                                        <td class="text-left"><img src="<?php echo $obj->getVehicleImage($v['model']); ?>" alt="<?php echo $v['model'] ?>" /></td>
                                        <td class="align-middle"><?php echo $obj->getVehicleName($v['model']); ?></td>
                                        <td class="align-middle"><?php echo $locked; ?></td>
                                        <td class="align-middle"><?php echo number_format($v['mileage'], 2); ?>km</td>
                                        <td class="align-middle"><?php echo ceil($v['fuel']); ?>%</td>
                                        <td class="align-middle"><a href="#" data-bs-toggle="modal" data-id="<?php echo $v['id']; ?>" data-bs-target="#inventoryModal">Inventory</a></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>  
                    <?php else: ?>
                        <div class='alert alert-danger'>
                            This character doesn't own any vehicles.
                        </div>             
                    <?php endif; ?>

                <!-- Container ends here -->
                </div>
            <!-- Emulate Card Ends here -->
            </div>

        <!-- Container Ends here -->
        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>

    <script>
        $(document).on('click', '[data-bs-toggle="modal"]', function() {
            var id = $(this).data('id');

            $('#modalDataId').text(id);

            var dataString='vehid='+ id;
            
            $.ajax(
            {
                type:"post",
                url: "ajax/ajax_vehicle_inventory.php",
                data: dataString,
                success: function(data)
                {
                    $("#ajax").html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //console.log(textStatus, errorThrown);
                }
            });
        });
    </script>
</body>
</html>