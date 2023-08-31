<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - Home Page</title>

    <style>
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            overflow: auto;

            position: relative;
            width: 100%;
            min-height: 100%;
            background-image: url(<?php echo DIR_PICTURES . 'background.png'; ?>);
            background-repeat: no-repeat;
            background-size: cover;
            display: block;
            background-attachment: fixed;
        }
    </style>
</head>

<body>
    <div class="container-fluid vh-100">

        <div class="row d-flex justify-content-center align-items-center h-50">
                    
            <div class="col-lg-5 col-xs-12">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <div class="container">
                            <form method="POST">
                                <label class="form-label">Username</label>
                                <input class="form-control" type="text" placeholder="Username or Email" name="email" id="emailForm" />

                                <label class="form-label">Password</label>
                                <input class="form-control" type="password" placeholder="Password" name="password" id="passForm" />

                                <div class="py-3 mt-3">
                                    <input type="submit" value="Login" class="btn btn-info w-100 text-white" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

    </div>
</body>

<?php require(DIR_INC . 'footer.php') ?>

</html>