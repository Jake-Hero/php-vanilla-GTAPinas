<style>
    h1 {
        font-size: 200px;
        line-height: 1 !important;
    }

    h2 {
        font-size: 40px;
        line-height: 0 !important;
    }

    .content {
        text-align: center;
        padding: 50px;
    }

    #back_home {
        margin-top: 50px;
    }

    i {
        font-size: 200px;
    }
</style>

<head>
    <title><?php echo SITE_NAME; ?> - Page not found</title>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">
        <!-- Container -->
            <div class="row py-5">
                <div class="float-none mx-auto">
                    <div class="shadow-lg p-3 mb-5 bg-light rounded">
                        <div class="content">
                            <i class="far fa-meh"></i>
                            <h1>404</h1>
                            <h2>PAGE NOT FOUND</h2>

                            <a id="back_home" href="<?php echo SITE_URL; ?>/index.php" class="btn border border-dark">Back to Homepage</a>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Container Ends here -->
        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>
</body>