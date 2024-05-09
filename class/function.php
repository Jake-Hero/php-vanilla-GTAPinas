<?php
class ucpProject {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // testing purposes.
    function test() {
        return var_dump($_SESSION);
    }
    
    // Check if user is logged in based on UID session.
    function isLoggedIn()
    {
        return isset($_SESSION['UID']);
    }

    // Provide success, error, warning messages.
    function GetMessage($subject, $message, $type)
    {
        switch($type)
        {
            case 1: // Error
            {
                echo "
                <div class='alert alert-danger' id='message'>
                    <strong>$subject</strong> 
                    <div>$message</div>
                </div>
                ";
            } break;
            case 2: // Warning
            {
                echo "
                <div class='alert alert-warning' id='message'>
                    <strong>$subject</strong> 
                    <div>$message</div>
                </div>
                ";
            } break;
            case 3: // Success
            {
                $_SESSION['success_message'] = "
                <div class='alert alert-success' id='message'>
                    <strong>$subject</strong> 
                    <div>$message</div>
                </div>
                ";

                echo $_SESSION['success_message'];
            } break;
            case 4: // Error (without fadeout)
            {
                echo "
                <div class='alert alert-danger'>
                    <strong>$subject</strong> 
                    <div>$message</div>
                </div>
                ";
            } break;
            case 5: // Warning (without fadeout)
            {
                echo "
                <div class='alert alert-warning'>
                    <strong>$subject</strong> 
                    <div>$message</div>
                </div>
                ";
            } break;
            case 6: // Success (without fadeout)
            {
                echo "
                <div class='alert alert-success'>
                    <strong>$subject</strong> 
                    <div>$message</div>
                </div>
                ";
            } break;
        }
    }

    // Login user.
    function UserLogin()
    {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $query = $this->pdo->prepare("SELECT * FROM accounts WHERE email = :email OR username = :email");
            $query->execute(array(':email' => $email));

            if ($query->rowCount() > 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    // Verify password using bcrypt
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['UID'] = $row['id'];
                        $_SESSION['USER'] = $row;

                        echo '<script>window.location=\'user/dashboard.php\'</script>';
                        return; // Exit function after successful login
                    } else {
                        $this->GetMessage("Oops!", "Looks like your login credentials are incorrect. Please try again!", 1);
                        return; // Exit function if password doesn't match
                    }
                }
            } else {
                $this->GetMessage("Oops!", "Your account does not exist in our database. Please try again later!", 1);
                return; // Exit function if account doesn't exist
            }
        } else {
            $this->GetMessage("Oh snap!", "Make sure you fill out all the fields below. Please try again!", 1);
            return; // Exit function if fields are empty
        }
    }

    // Save user's settings
    function SettingSave() 
    {
        $new_pass = $_POST['newpassword'];
        $cur_pass = $_POST['password'];

        if (!empty($cur_pass)) {
            if(password_verify($cur_pass, $this->fetchData('accounts', 'password', 'id', $_SESSION['UID']))) {
                if(!empty($new_pass)) {
                    $new_pass = password_hash($new_pass, PASSWORD_BCRYPT, ['cost' => 12]);

                    $this->saveData('accounts', 'password', $new_pass, 'id', $_SESSION['UID']);
                }

                $this->GetMessage("Save!", "Settings Saved.", 3);
                
                echo "<meta http-equiv='refresh' content='0'>";
                return;
            } else {
                $this->GetMessage("Oops!", "Your current password is wrong!", 1);
                return;
            }
        } else {
            $this->GetMessage("Oops!", "Current password must be filled.", 1);
            return;
        }
    }

    // fetch a character's data.
    function getCharacterData($id) {
        $result = $this->pdo->prepare("SELECT * FROM characters WHERE id = :id LIMIT 1;");
        $result->execute(array(':id' => $id));
        
        if ($result->rowCount() > 0) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return null; // No data found
        }
    }

    // calculate all hours played from all the characters from the account.
    function calculateTotalHours($uid) {
        // limit the characters fetching to three only. (Design Compability & Game Script compability)
        $result = $this->pdo->prepare("SELECT hours FROM characters WHERE uid = :uid LIMIT 3;");
        $result->execute(array(':uid' => $_SESSION['UID']));

        $hours = 0;

        if ($result->rowCount() > 0) {
            while ($row = $result->fetchColumn()) {
                $hours = $hours + $row;
            }
        }

        return $hours;
    }

    // checks the characyer's proper age. (converted from PAWN language (server's game script) to PHP)
    function calculateCharacterAge($timestamp) {
        //$timestamp = date('Y-m-d', strtotime($timestamp));

        $m = date('m');
        $d = date('d');
        $y = date('Y');

        $month = date('m', strtotime($timestamp));
        $day = date('d', strtotime($timestamp));
        $year = date('Y', strtotime($timestamp));
        $age = null;

        $age = ($month >= $m && $day >= $d) ? ($y - $year) : ($y - $year - 1);
        return $age;	
    }

    // fetch all the businesses owned by the character.
    function fetchBusinesses($id) {
        $result = $this->pdo->prepare("SELECT * FROM business WHERE owner = :id");
        $result->execute(array(':id' => $id));

        $businesses = array();

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $businesses[] = $row;
            }
        }

        return $businesses;
    }

    // fetch a business' data. (ajax)
    function fetchBizData($bid)
    {
        $query = $this->pdo->prepare("SELECT * FROM business WHERE id = :bid");
        $query->execute(array(':bid' => $bid));

        if ($query->rowCount() > 0) {
            $biz = array();

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $biz[] = $row;
            }
            return $biz;
        } else {
            die;
        }
    }

    // fetch a business' products prices. (ajax)
    function fetchBizPrices($bid)
    {
        $query = $this->pdo->prepare("SELECT * FROM business_prices WHERE bizid = :bid");
        $query->execute(array(':bid' => $bid));

        if ($query->rowCount() > 0) {
            $prices = array();

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $prices[] = $row;
            }
            return $prices;
        } else {
            die;
        }
    }
    
    // fech business' prooucts & prices (ajax)
    function fetchBizProducts($bid) {
        $data = $this->fetchBizPrices($bid);
        $type = $this->fetchData('business', 'type', 'id', $bid) - 1;
        $products = [];
    
        if(!empty($data)) {
            $products_name = array(
                array( // Gas Station
                    "Portable Radio",
                    "Mobile Phone",
                    "GPS", 
                    "Rope", 
                    "Lottery Ticket",
                    "Mask", 
                    "Simcard", 
                    "Prepaid Credit", 
                    "Fishing Rod", 
                    "Dice", 
                    "Baking Soda", 
                    "Cigarette",
                    "Lighter", 
                    "Fuel Can"
                ),
                array( // Convenience Store
                    "Portable Radio",
                    "Mobile Phone",
                    "GPS", 
                    "Rope", 
                    "Lottery Ticket",
                    "Mask", 
                    "Simcard", 
                    "Prepaid Credit", 
                    "Fishing Rod", 
                    "Dice", 
                    "Baking Soda", 
                    "Cigarette",
                    "Lighter", 
                    "Fuel Can"
                ),
                null, // Vehicle Dealership
                array( // Clothing Store
                    "Clothe",
                    "Accessories"
                ),
                array( // Gun Store
                    "Baseball Bat", 
                    "9mm", 
                    "Shotgun", 
                    "Light Armour"
                ),
                array( // Restaurant
                    "Water Bottle", 
                    "Soda", 
                    "Slice of Pizza", 
                    "Large Burger"
                ),
                null, // Car Autoshop
                array( // Bar
                    "Water Bottle", 
                    "Soda", 
                    "Slice of Pizza", 
                    "Large Burger"
                )
            );

            $product = $products_name[$type-1];

            foreach($data as $row) {
                $price = [];
                for($i = 1; $i <= 20; $i++) {
                    $prices = 'prices' . $i;
                    $price[] = $row[$prices];
                }

                foreach($product as $pn) {
                    $products[] = array('name' => $pn, 'price' => array_shift($price));
                }
            }
        }
    
        return $products;
    }

    // fetch business's type
    function getBizType($type) {
        $type_name = "undefined";

        switch($type) {
            case 1: $type_name = "Gas Station"; break;
            case 2: $type_name = "Convenience Store"; break;
            case 3: $type_name = "Vehicle Dealer Ship"; break;
            case 4: $type_name = "Clothing Store"; break;
            case 5: $type_name = "Gun Store"; break;
            case 6: $type_name = "Restaurant"; break;
            case 7: $type_name = "Car Autoshop"; break;
            case 8: $type_name = "Bar"; break;
        }

        return $type_name;
    }

    // fetch all the houses owned by the character.
    function fetchHouses($id) {
        $result = $this->pdo->prepare("SELECT * FROM properties WHERE owner = :id");
        $result->execute(array(':id' => $id));

        $houses = array();

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $houses[] = $row;
            }
        }

        return $houses;
    }

    // fetch a house's data. (ajax)
    function fetchHouseData($hid)
    {
        $query = $this->pdo->prepare("SELECT * FROM properties WHERE id = :hid");
        $query->execute(array(':hid' => $hid));

        if ($query->rowCount() > 0) {
            $houses = array();

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $houses[] = $row;
            }
            return $houses;
        } else {
            die;
        }
    }

    // fech house's weapons (ajax)
    function fetchHouseWeapons($hid) {
        $data = $this->fetchHouseData($hid);
        $weapons = array();
    
        if(!empty($data)) {
            foreach($data as $row) {
                for($i = 0; $i < 5; $i++) {
                    $weapon_key = 'weapon_' . $i;
                    $ammo_key = 'ammo_' . $i;
                    
                    if($row[$weapon_key] > 0) {
                        $weapons[] = array('weapon' => $row[$weapon_key], 'ammo' => $row[$ammo_key]);
                    }
                }
            }
        }
    
        return $weapons;
    }
    
    // count house furniture.
    function countFurnitures($hid) {
        $result = $this->pdo->prepare("SELECT * FROM furniture WHERE houseid = :id");
        $result->execute(array(':id' => $hid));    
        
        return $result->rowCount();
    }

    // fetch all the characters assigned to that account ID.
    function getCharacters($uid) {
        // limit the characters fetching to three only. (Design Compability & Game Script compability)
        $result = $this->pdo->prepare("SELECT * FROM characters WHERE uid = :uid LIMIT 3;");
        $result->execute(array(':uid' => $_SESSION['UID']));

        $characters = array();

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $characters[] = $row;
            }
        }

        return $characters;
    }

    // fetch data to specified table & select column based on the given column and value.
    function fetchData($table, $select_column, $column, $value) {
        $result = $this->pdo->prepare("SELECT $select_column FROM $table WHERE $column = :value");
        $result->execute(array(':value' => $value));
        
        $data = null;

        if ($result->rowCount() > 0) {
            $data = $result->fetchColumn();
        }

        return $data;
    }

    // save data to specified table & select column based on the given column and value.
    function saveData($table, $fieldname, $fieldvalue, $column, $colval) {
        $result = $this->pdo->prepare("UPDATE $table SET $fieldname = :value WHERE $column = :colval");
        $result->execute(array(':value' => $fieldvalue, ':colval' => $colval));
        return;
    }

    // fetch skin image based on the given skinID.
    function getSkin($skin) {
        // question mark by default.
        $skin_file = SITE_URL . '/assets/pictures/skins/undefined.png';

        if(($skin >= 0) && ($skin != 74) && ($skin <= 311)) {
            // change if a valid skin is detected. scan through the pictures/skins folder.
            $skin_file = SITE_URL . '/assets/pictures/skins/' . $skin . '.png';
        }
        return $skin_file;
    }

    // fetch weapon's name based on the given ID.
    function getWeaponName($weaponid) {
        
        $weapons = array(
            'Fist',
            'Brass Knuckles',
            'Golf Club',
            'Nightstick',
            'Knife',
            'Baseball Bat',
            'Shovel',
            'Pool Cue',
            'Katana',
            'Chainsaw',
            'Purple Dildo',
            'Dildo',
            'Vibrator',
            'Silver Vibrator',
            'Flowers',
            'Cane',
            'Grenade',
            'Tear Gas',
            'Molotov Cocktail',
            null, // 19
            null, // 20
            null, // 21
            '9mm',
            'Silenced 9mm',
            'Desert Eagle',
            'Shotgun',
            'Sawnoff Shotgun',
            'Combat Shotgun',
            'Uzi',
            'MP5',
            'AK-47',
            'M4',
            'Tec-9',
            'Country Rifle',
            'Sniper Rifle',
            'RPG',
            'HS Rocket',
            'Flamethrower',
            'Minigun',
            'Satchel Charge',
            'Detonator',
            'Spraycan',
            'Fire Extinguisher',
            'Camera',
            'Night Vision Goggles',
            'Thermal Goggles',
            'Parachute'
        );

        $weapon_name = NULL;

        $weapon_name = $weapons[$weaponid];
        return $weapon_name;
    }
    
    // fetch account's donator rank
    function getDonatorRank($rank) {
        $rank_name = "Not Subscribed";

        switch($rank) {
            case 1: $rank_name = "Silver Donator"; break;
            case 2: $rank_name = "Gold Donator"; break;
            case 3: $rank_name = "Platinum Donator"; break;
        }

        return $rank_name;
    }

    // Throw 404 error
    function throw404() {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        include __DIR__ . "/../404.php";
        return die();
    }
}

?>