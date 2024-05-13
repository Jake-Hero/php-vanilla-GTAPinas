<?php 
    require __DIR__ . '/../autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/../header.php";

    if(($obj->isLoggedIn() == false) || ($obj->isUSerAdmin() < 2)) {
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

    <title><?php echo SITE_NAME; ?> - Ban List</title>
</head>

<div class="modal fade" id="unbanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Ban ID # <span id="modalDataId"></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                Do you want to unban:
                
                <hr>

                <p id="ajax"></p>
            </div>

            <div class="modal-footer">
                <button type="button" id="unbanBtn" data-id="" class="btn btn-success" data-bs-dismiss="modal">Unban</button>
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

            <div class='alert alert-warning'>
                <strong>Please note that the date format displayed in the table below is (MM-DD-YYYY).</strong> 
            </div>

            <div class="shadow-lg p-3 mb-5 bg-light rounded">
            <!-- Emulate Card -->

                <div class="table-responsive">
                    <table id="banTable" class="table table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center"># ID</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Banned By</th>
                                <th class="text-center">Reason</th>
                                <th class="text-center">Expiration</th>
                                <th class="text-center"></th>
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
            $('#banTable').dataTable({
                "processing": true,
                "ajax": "ajax/ajax_fetch_bans.php",
                "columns": [
                    {data: 'id'},
                    {
                        data: 'ban_timestamp',
                        render: function(data, type) {
                            var date = new Date(data * 1000);
                            var formattedDate = (date.getMonth() + 1) + '-' + date.getDate() + '-' + date.getFullYear() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
                            return formattedDate;
                        }
                    },
                    {data: 'username'},
                    {data: 'ban_admin'},
                    {data: 'ban_reason'},
                    {
                        data: 'ban_expire_timestamp',
                        render: function(data) {
                            var date = new Date(data * 1000);
                            var formattedDate = (date.getMonth() + 1) + '-' + date.getDate() + '-' + date.getFullYear() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
                            var realData = (data > 0) ? formattedDate : "Indefinite";
                            return realData;
                        }
                    },
                    {
                        render: function (data, type, row, meta) {
                            var json = encodeURIComponent(JSON.stringify(row));
                            return '<a href="#" data-bs-toggle="modal" data-array="' + json + '" data-bs-target="#unbanModal">Unban</a>';
                        }
                    }
                ]
            });

            $('#unbanModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); 
                var json = button.data('array'); 
                var row = JSON.parse(decodeURIComponent(json));

                $('#modalDataId').text(row.id);

                $("#unbanBtn").attr('data-id', row.username);

                var date = new Date(row.ban_expire_timestamp * 1000);
                var formattedDate = (date.getMonth() + 1) + '-' + date.getDate() + '-' + date.getFullYear() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
                var realData = (row.ban_expire_timestamp > 0) ? formattedDate : "Indefinite";
                var html = "<p>User: <b>" + row.username + "</b><br/>" + "Reason: <b>" + row.ban_reason + "</b><br/>" + "Banned by: <b>" + row.ban_admin + "</b><br/>" + "Expiration: <b>" + realData + "</b></p>";

                $('#ajax').html(html);
            });

            $('#unbanBtn').on('click', function() {
                var username = $(this).attr('data-id');

                Swal.fire({
                    title: "Unbanned",
                    text: "Successfully unbanned.",
                    icon: "success"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var dataString='username='+ username;
                
                        $.ajax(
                        {
                            type:"post",
                            url: "ajax/ajax_unban.php",
                            data: dataString,
                            success: function(data)
                            {
                                //$("#ajax").html(data);
                                location.reload();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>