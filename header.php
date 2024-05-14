<!-- Website Icon -->
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITE_URL; ?>/favicon-32x32.png">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top shadow-5-strong mb-5">
    <div class="container">
        <a class="navbar-brand" href="<?php echo SITE_URL; ?>/index.php">
            <img src="<?php echo SITE_URL; ?>/assets/pictures/gtapinas_logo.png" alt="GTAPINASLOGO" height="50">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <?php if($obj->isLoggedIn() == true): ?>
        <div class="collapse navbar-collapse" id="navbarSupportedContent" style="width: 65%">
            <ul class="navbar-nav mr-auto">  
        <?php else: ?>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">       
        <?php endif; ?>
                <li class="nav-tem">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/index.php">Home</a>
                </li>

                <li class="nav-tem">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/about.php">About</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Highscores
                    </a>        

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo SITE_URL; ?>/highscore/playingtime.php">Active Players</a>
                        <a class="dropdown-item" href="<?php echo SITE_URL; ?>/highscore/wealthiest.php">Wealthiest Players</a>
                        <a class="dropdown-item" href="<?php echo SITE_URL; ?>/highscore/skins.php">Used Skins</a>
                        <a class="dropdown-item" href="<?php echo SITE_URL; ?>/highscore/vehiclemodels.php">Popular Vehicle Models</a>
                    </div>
                </li>  

                <?php if($obj->isLoggedIn() == false): ?>
                <li class="nav-tem">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/user/login.php">Login</a>
                </li>
                <?php endif; ?>

                <!-- li class="nav-tem">
                    <a class="nav-link" href="./?page=donate">Donate</a>
                </li -->

                <li class="nav-tem">
                    <a class="nav-link" href="https://gtapinas.xyz/discord">Discord</a>
                </li>

            </ul>   
        </div>

        <?php if($obj->isLoggedIn() == true): ?>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">       
                
                <!-- Admin Panel -->
                <?php if($obj->isUserAdmin() > 0): ?>
                <li class="nav-tem">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/index.php">Admin Panel</a>
                </li>
                <?php endif; ?>

                <!-- My Characters -->
                <li class="nav-tem">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/user/dashboard.php">My Characters</a>
                </li>

                <!-- User's Name -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $_SESSION['username']; ?>
                    </a>        

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo SITE_URL; ?>/user/settings.php">Settings</a>
                        <a class="dropdown-item" href="<?php echo SITE_URL; ?>/logout.php">Logout</a>
                    </div>
                </li>  

            </ul>   
        </div>
        <?php endif; ?>
    </div>
</nav>