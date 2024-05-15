<?php 
    require __DIR__ . '/../autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/../header.php";

    if($obj->isLoggedIn() == false || $obj->isUSerAdmin() < 1) {
        header("Location: " . SITE_URL . "/index.php");
        die;
    }

    // keep the variable '$cid' consitently named. So account_header.php can work properly.
    $cid = $_GET['id'];
    // fetch the character's houses
    $houses = $obj->fetchHouses($cid);
    
    $user = $obj->getCharacterData($cid);

    // if $user is null or
    // if no IDs were specified = throw 404 error.
    if(empty($user) || !isset($cid)) {
        //header("Location:" . SITE_URL . "/user/dashboard.php");
        $obj->throw404();
    }

    $page = 'characters.php';
    $page_name = "Characters";

    $owner = $obj->fetchData('characters', 'charname', 'id', $cid);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../"/>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME . ' - ' . $owner ?>'s Houses</title>
</head>

<div class="modal fade" id="inventoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Inventory for House ID: <span id="modalDataId"></span></h1>
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
            <?php require_once __DIR__ . '/../admin_header.php'; ?>
            <?php include_once __DIR__ . '/character_header.php'; ?>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <h1 class="text-center mb-4 mt-3">
                    <?php echo $owner; ?>'s Houses
                </h1>
            <!-- Emulate Card Ends here -->
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->
                <div class="container mb-3">
                <!-- Initiate container -->
                    
                    <?php if(!empty($houses)) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Level</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Furnitures</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <?php foreach($houses as $house): ?>
                                    <?php $locked = ($house['locked']) ? ("Locked") : ("Unlocked"); ?>

                                    <tr>
                                        <td><i class="fas fa-home" style="font-size: 20px;"></i></td>
                                        <td><?php echo number_format($house['id']); ?></td>
                                        <td><?php echo number_format($house['level']); ?></td>
                                        <td>$<?php echo number_format($house['price']); ?></td>
                                        <td><?php echo $locked; ?></td>
                                        <td><?php echo $obj->countFurnitures($house['id']); ?></td>
                                        <td><a id="inventory" href="#" data-bs-toggle="modal" data-id="<?php echo $house['id']; ?>" data-bs-target="#inventoryModal">Inventory</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>  
                    <?php else: ?>
                        <div class='alert alert-danger'>
                            This character doesn't own any properties.
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

            var dataString='houseid='+ id;
            
            $.ajax(
            {
                type:"post",
                url: "ajax/ajax_house_inventory.php",
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