<?php 
    require __DIR__ . '/../autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/../header.php";

    if($obj->isLoggedIn() == true) {
        header("Location: dashboard.php");
        die;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Home Page</title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-autos">
        <div class="container-fluid">
            <div class="row py-5">
                <div class="col-lg-3 col-md-3 col-xs-12 float-none mx-auto">
                    <div class="shadow-lg p-3 mb-5 bg-light rounded">
                        <div class="card-header mb-5">
                            <h5 class="text-center">
                                <i class="fa fa-user"></i> Account Login
                            </h5>
                        </div>

                        <div class="card-body">
                            <form method="POST">
                                <div id="ajax"></div>

                                <div class="form-group">
                                    <label class="form-label">Username</label>
                                    <input class="form-control" type="text" placeholder="Email Address" name="email" id="emailForm" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input class="form-control" type="password" placeholder="Password" name="password" id="passForm" />
                                </div>

                                <div class="py-3 mt-3">
                                    <button type="submit" class="btn btn-info w-100 text-white" onclick="return loginUser()">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>

	<script type="text/javascript">
        /*$(document).ready(function()
        {
            setInterval(function(){
                $("#message").slideUp("slow");
            }, 6000);
        });*/
	</script>

    <script src="<?php echo SITE_URL; ?>/js/account.js" type="text/javascript"></script>
</body>

</html>