<?php 
    require __DIR__ . '/autoload.php';

    $dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
    $pdo = $dbClass->getConnection();
    $obj = new ucpProject($pdo);
    require_once __DIR__ . "/header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="./"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <title><?php echo SITE_NAME; ?> - About</title>

    <style>
    p {
        white-space: pre-wrap;
    }

    b {
        color: green;
    }
    </style>
</head>

<body>
    <main role="main" class="flex-grow-1 overflow-auto">
        <div class="container">

            <div class="row d-flex justify-content-center align-items-center">
                        
                <div class="shadow-lg p-3 mb-5 bg-light rounded">
                    <div class="container">
                        <h3>What is <b class="text-danger">GTA Pinas Roleplay</b>?</h3>

<p>
<center>
<img src="<?php echo SITE_URL; ?>/assets/pictures/screenshots/miners.jpg" alt="playermining" class="img-fluid mb-5" />
</center>

A roleplay server established in 2020, founded by <b>Justine Ramos (Cipher)</b>, <b>Dizeuce</b> and <b>aezakmi</b>.

Set in the map of San Andreas (Los Santos, San Fierro, Las Venturas), GTA Pinas Roleplay is a game server for San Andreas Multiplayer (SA-MP), a third-party modification 
for Grand Theft Auto: San Andreas, offering a fun roleplaying experience for everyone.

<center>
<img src="<?php echo SITE_URL; ?>/assets/pictures/screenshots/playerbase.png" alt="playercount" class="img-fluid mt-5 mb-5" />
</center>

With more than 2,600 registered accounts in its first stint and a daily player count of over 100 when it was still active.

The first server script used was developed by <b>Cipher</b> and <b>aezakmi</b>.

GTA Pinas Roleplay became a branch of Renegade Community, after it was founded in August 2023.
</p>

                    </div>
                </div>
                
            </div>

        </div>
    </main>

    <?php require DIR_INC . 'footer.php'; ?>
</body>

</html>