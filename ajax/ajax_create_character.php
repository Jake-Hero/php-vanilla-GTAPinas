<?php
session_start();

require __DIR__ . '/../class/db.php';
require __DIR__ . '/../class/function.php';

$dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
$pdo = $dbClass->getConnection();
$obj = new ucpProject($pdo);

$uid = $_SESSION['UID'];
$slot = intval($_POST['slot']);
$name = $_POST['name'];
$gender = intval($_POST['gender']);
$birthday = $_POST['birthday'];
$cash = $obj->fetchConfigData('START_MONEY');
$bank = $obj->fetchConfigData('START_BANK');
$x = $obj->fetchConfigData('firstSpawnX');
$y = $obj->fetchConfigData('firstSpawnY');
$z = $obj->fetchConfigData('firstSpawnZ');
$ang = $obj->fetchConfigData('firstSpawnA');
$skin = intval($_POST['skin']);
$age = $obj->calculateCharacterAge($birthday);

if(empty($uid)) {
    $obj->GetMessage("Oops!", "An error occured while trying to fetch your userid.", 1);
} else if(empty($slot)) {
    $obj->GetMessage("Oops!", "An error occured while trying to fetch your character slotid.", 1);
} else if(empty($name)) {
    $obj->GetMessage("Oops!", "Fill up the character name field.", 1);
} else if(empty($gender)) {
    $obj->GetMessage("Oops!", "Fill up the gender field.", 1);
} else if(empty($birthday)) {
    $obj->GetMessage("Oops!", "Fill up the birthday field.", 1);
} else if(empty($skin)) {
    $obj->GetMessage("Oops!", "Fill up the skin field.", 1);
} else if($age < 18) {
    $msg = "Your character must be 18 years old and above. (Your character's age is " . $age . " years old)";
    $obj->GetMessage("Oops!", $msg, 1);
} else {
    $existing = $obj->fetchData('characters', 'id', 'charname', $name);

    if(!empty($existing)) {
        $obj->GetMessage("Oops!", "This name is taken, please select another one.", 1);
    } else {
        if(!$obj->isRpNickname($name)) {
            $obj->GetMessage("Oops!", "The character name you specified must contain underscore ('_') not space.", 1);
        } else {
            $query = "INSERT INTO `characters` (`uid`, `slot`, `charname`, `gender`, `birthday`, `cash`, `bank`, `last_skin`, `last_pos_x`, `last_pos_y`, `last_pos_z`, `last_pos_a`) 
                    VALUES(:uid, :slot, :name, :gender, :birthday, :cash, :bank, :skin, :x, :y, :z, :ang)";
            try {
                $stmt = $pdo->prepare($query);
                $stmt->execute(array(
                    ':uid' => $uid, 
                    ':slot' => $slot, 
                    ':name' => $name, 
                    ':gender' => $gender, 
                    ':birthday' => $birthday, 
                    ':cash' => $cash, // Assuming $starting_cash is the same as $cash
                    ':bank' => $bank, // Assuming $starting_bank is the same as $bank
                    ':skin' => $skin, 
                    ':x' => $x, 
                    ':y' => $y, 
                    ':z' => $z, 
                    ':ang' => $ang
                ));

                echo 'Success';
            } catch (PDOException $e) {
                // Handle the exception
                echo "Error: " . $e->getMessage();
            }
        }
    }
}

?>