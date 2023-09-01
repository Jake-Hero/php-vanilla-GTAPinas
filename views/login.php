<?php 
    require(DIR_BASE . "inc/header_checker.php");
    checkLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Home Page</title>
</head>

<body>
    <div class="container-fluid vh-100">

        <div class="row py-5">
            <div class="col-lg-3 col-md-3 col-xs-12 float-none mx-auto">
                <div class="card">
                    <div class="card-header">
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

	<script type="text/javascript">
        $(document).ready(function()
        {
            setInterval(function(){
                $("#message").slideUp("slow");
            }, 6000);
        });
	</script>

    <script src="./js/account.js" type="text/javascript"></script>
</body>

<?php require(DIR_INC . 'footer.php') ?>

</html>