<?php 
    require __DIR__ . '/../autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/../header.php";

    $page = 'characters.php';
    $page_name = "Characters";

    if(($obj->isLoggedIn() == false)) {
        header("Location: " . SITE_URL . "/index.php");
        die;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../"/>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js" charset="utf8" type="text/javascript"></script>

    <title><?php echo SITE_NAME; ?> - Characters List</title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->
            <?php require_once __DIR__ . '/../admin_header.php'; ?>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->

                <div class="table-responsive">
                    <table id="usersTable" class="table table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center"># ID</th>
                                <th class="text-center">Character Name</th>
                                <th class="text-center">Owner</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            <!-- Emulate Card Ends here -->
            </div>

        <!-- Container Ends here -->
        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>

    <script>
        $(document).ready(function() {
            var url = "<?php echo SITE_URL; ?>";

            $('#usersTable').dataTable({
                "processing": true,
                "ajax": "ajax/ajax_fetch_characters.php",
                "columns": [
                    {data: 'id'},
                    {
                        data: 'charname',
                        render: function (data, type, row, meta) {
                            return '<a href="' + url + '/admin/character.php?id=' + row.id + '">' + row.charname + '</a>';
                        }
                    },
                    {data: 'owner_name'}
                ]
            });
        });
    </script>
</body>
</html>