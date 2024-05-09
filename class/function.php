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
        $data = null;

        if(isset($id)) {
            $result = $this->pdo->prepare("SELECT * FROM characters WHERE id = :id LIMIT 1;");
            $result->execute(array(':id' => $id));
            
            if ($result->rowCount() > 0) {
                $data = $result->fetch(PDO::FETCH_ASSOC);
            }
        }
        return $data;
    }

    // calculate all hours played from all the characters from the account.
    function calculateTotalHours($uid) {
        // limit the characters fetching to three only. (Design Compability & Game Script compability)
        $hours = 0;

        if(isset($uid)) {
            $result = $this->pdo->prepare("SELECT hours FROM characters WHERE uid = :uid LIMIT 3;");
            $result->execute(array(':uid' => $_SESSION['UID']));

            if ($result->rowCount() > 0) {
                while ($row = $result->fetchColumn()) {
                    $hours = $hours + $row;
                }
            }
        }
        return $hours;
    }

    // checks the characyer's proper age. (converted from PAWN language (server's game script) to PHP)
    function calculateCharacterAge($timestamp) {
        $age = null;

        if(isset($timestamp)) {
            //$timestamp = date('Y-m-d', strtotime($timestamp));

            $m = date('m');
            $d = date('d');
            $y = date('Y');

            $month = date('m', strtotime($timestamp));
            $day = date('d', strtotime($timestamp));
            $year = date('Y', strtotime($timestamp));

            $age = ($month >= $m && $day >= $d) ? ($y - $year) : ($y - $year - 1);
        }
        return $age;	
    }

    // fetch all the businesses owned by the character.
    function fetchBusinesses($id) {
        $businesses = [];

        if(isset($id)) {
            $result = $this->pdo->prepare("SELECT * FROM business WHERE owner = :id");
            $result->execute(array(':id' => $id));

            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $businesses[] = $row;
                }
            }
        }
        return $businesses;
    }

    // fetch a business' data. (ajax)
    function fetchBizData($bid)
    {
        $biz = [];

        if(isset($bid)) {
            $query = $this->pdo->prepare("SELECT * FROM business WHERE id = :bid");
            $query->execute(array(':bid' => $bid));

            if ($query->rowCount() > 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $biz[] = $row;
                }
            }
        }
        return $biz;
    }

    // fetch a business' products prices. (ajax)
    function fetchBizPrices($bid)
    {
        $prices = [];

        if(isset($bid)) {
            $query = $this->pdo->prepare("SELECT * FROM business_prices WHERE bizid = :bid");
            $query->execute(array(':bid' => $bid));

            if ($query->rowCount() > 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $prices[] = $row;
                }
            }
        }
        return $prices;
    }

    // fetch a dealership's vehicles, if it is a dealership business. (ajax)
    function fetchBizCars($bid)
    {
        $vehicles = [];

        if(isset($bid)) {
            $type = $this->fetchData('business', 'type', 'id', $bid);

            if($type == 3) {
                $query = $this->pdo->prepare("SELECT * FROM dealervehicles WHERE bizid = :bid");
                $query->execute(array(':bid' => $bid));

                if ($query->rowCount() > 0) {
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $vehicles[] = $row;
                    }
                }
            }
        }
        return $vehicles;
    }
    
    // fetch business' prooucts & prices (ajax)
    function fetchBizProducts($bid) {
        $products = [];

        if(isset($bid)) {
            $data = $this->fetchBizPrices($bid);
            $type = $this->fetchData('business', 'type', 'id', $bid) - 1;
        
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

                $product = $products_name[$type];

                foreach($data as $row) {
                    $price = [];
                    for($i = 1; $i <= 20; $i++) {
                        $prices = 'prices' . $i;
                        $price[] = $row[$prices];
                    }

                    if(isset($product)) {
                        foreach($product as $pn) {
                            $products[] = array('name' => $pn, 'price' => array_shift($price));
                        }
                    } else {
                        if($type == 2) {
                            // this could be a dealership so let's fetch the vehicles being sold @ dealership.
                            $vehicles = $this->fetchBizCars($bid);

                            if(isset($vehicles)) {
                                foreach($vehicles as $v) {
                                    $products[] = array('name' => $v['model'], 'price' => $v['price']);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $products;
    }

    // fetch business's type
    function getBizType($type) {
        if(isset($type)) {
            $type_name = "undefined";

            switch($type) {
                case 1: $type_name = "Gas Station"; break;
                case 2: $type_name = "Convenience Store"; break;
                case 3: $type_name = "Vehicle Dealership"; break;
                case 4: $type_name = "Clothing Store"; break;
                case 5: $type_name = "Gun Store"; break;
                case 6: $type_name = "Restaurant"; break;
                case 7: $type_name = "Car Autoshop"; break;
                case 8: $type_name = "Bar"; break;
            }
        }
        return $type_name;
    }

    // fetch all the houses owned by the character.
    function fetchHouses($id) {
        $houses = [];

        if(isset($id)) {
            $result = $this->pdo->prepare("SELECT * FROM properties WHERE owner = :id");
            $result->execute(array(':id' => $id));

            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $houses[] = $row;
                }
            }
        }
        return $houses;
    }

    // fetch a house's data. (ajax)
    function fetchHouseData($hid)
    {
        $houses = [];

        if(isset($hid)) {
            $query = $this->pdo->prepare("SELECT * FROM properties WHERE id = :hid");
            $query->execute(array(':hid' => $hid));

            if ($query->rowCount() > 0) {

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $houses[] = $row;
                }
            }
        }
        return $houses;
    }

    // fetch house's weapons (ajax)
    function fetchHouseWeapons($hid) {
        $weapons = [];

        if(isset($hid)) {
            $data = $this->fetchHouseData($hid);
        
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
        $characters = [];

        if(isset($uid)) {
            // limit the characters fetching to three only. (Design Compability & Game Script compability)
            $result = $this->pdo->prepare("SELECT * FROM characters WHERE uid = :uid LIMIT 3;");
            $result->execute(array(':uid' => $_SESSION['UID']));

            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $characters[] = $row;
                }
            }
        }
        return $characters;
    }

    // fetch data to specified table & select column based on the given column and value.
    function fetchData($table, $select_column, $column, $value) {
        $data = null;

        if(isset($table) && isset($select_column) && isset($column) && isset($value)) {
            $result = $this->pdo->prepare("SELECT $select_column FROM $table WHERE $column = :value");
            $result->execute(array(':value' => $value));
            
            if ($result->rowCount() > 0) {
                $data = $result->fetchColumn();
            }
        }
        return $data;
    }

    // save data to specified table & select column based on the given column and value.
    function saveData($table, $fieldname, $fieldvalue, $column, $colval) {
        if(isset($table) && isset($fieldname) && isset($fieldvalue) && isset($column) && isset($colval)) {
            $result = $this->pdo->prepare("UPDATE $table SET $fieldname = :value WHERE $column = :colval");
            $result->execute(array(':value' => $fieldvalue, ':colval' => $colval));
        }
        return;
    }

    // fetch skin image based on the given skinID.
    function getSkinImage($skin) {
        // question mark by default.
        $skin_file = SITE_URL . '/assets/pictures/skins/undefined.png';

        if(isset($skin)) {
            if(($skin >= 0) && ($skin != 74) && ($skin <= 311)) {
                // change if a valid skin is detected. scan through the pictures/skins folder.
                $skin_file = SITE_URL . '/assets/pictures/skins/' . $skin . '.png';
            }
        }
        return $skin_file;
    }

    // fetch all the vehicles owned by the character.
    function fetchVehicles($id) {
        $vehicles = [];

        if(isset($id)) {
            $result = $this->pdo->prepare("SELECT * FROM vehicles WHERE owner = :id");
            $result->execute(array(':id' => $id));

            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $vehicles[] = $row;
                }
            }
        }
        return $vehicles;
    }

    // fetch vehicle's weapons (ajax)
    function fetchVehicleWeapons($vid) {
        $weapons = [];

        if(isset($vid)) {
            $data = $this->fetchVehicles($vid);
        
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
        }
        return $weapons;
    }
    
    // fetch faction name based on the given factionID.
    function getCharacterFaction($uid) {
        $name = null;

        if(isset($uid)) {
            $result = $this->fetchData('characters', 'faction', 'id', $uid);

            if(isset($result) && $result >= 0) {
                $name = $this->fetchData('factions', 'name', 'factionid', $result);
            }
        }
        return $name;
    }

    // fetch faction rank based on the given rankID.
    function getFactionRank($faction, $rank) {
        $name = "Undefined";

        if(isset($faction) && isset($rank)) {
            $id = $this->fetchData('factions', 'id', 'name', $faction);

            $query = $this->pdo->prepare("SELECT name FROM factionranks WHERE factionid = :faction AND rank = :rank");
            $query->execute(array(':faction' => $id, ':rank' => $rank));

            if ($query->rowCount() > 0) {
                $name = $query->fetchColumn();
            }
        }
        return $name;
    }

    // fetch gang rank based on the given rankID.
    function getGangRank($gang, $rank) {
        $name = "Undefined";

        if(isset($gang) && isset($rank)) {
            $id = $this->fetchData('gangs', 'id', 'name', $faction);
            
            $query = $this->pdo->prepare("SELECT name FROM gangranks WHERE gangid = :gang AND rank = :rank");
            $query->execute(array(':gang' => $id, ':rank' => $rank));

            if ($query->rowCount() > 0) {
                $name = $query->fetchColumn();
            }
        }
        return $name;
    }

    // fetch gang name based on the given gangID.
    function getCharacterGang($uid) {
        $name = null;

        if(isset($uid)) {
            $result = $this->fetchData('characters', 'gang', 'id', $uid);

            if(isset($result) && $result >= 0) {
                $name = $this->fetchData('gangs', 'name', 'id', $result);
            }
        }
        return $name;
    }
    
    // fetch vehicle image based on the given vehicleID.
    function getVehicleImage($vid) {
        // question mark by default.
        $vehicle_file = SITE_URL . '/assets/pictures/vehicles/undefined.png';

        if(isset($vid)) {
            if(($vid >= 400) && ($vid <= 611)) {
                // change if a valid vehicle is detected. scan through the pictures/vehicles folder.
                $vehicle_file = SITE_URL . '/assets/pictures/vehicles/Vehicle_' . $vid . '.jpg';
            }
        }
        return $vehicle_file;
    }

    // fetch vehicle's model name based on the given modelID.
    function getVehicleName($model) {
        $vehicles = "undefined";

        if(isset($model)) {
            $vehicleNames = array(
                "Landstalker", "Bravura", "Buffalo", "Linerunner", "Perrenial", "Sentinel", "Dumper", "Firetruck", "Trashmaster", "Stretch",
                "Manana", "Infernus", "Voodoo", "Pony", "Mule", "Cheetah", "Ambulance", "Leviathan", "Moonbeam", "Esperanto", "Taxi",
                "Washington", "Bobcat", "Mr Whoopee", "BF Injection", "Hunter", "Premier", "Enforcer", "Securicar", "Banshee", "Predator",
                "Bus", "Rhino", "Barracks", "Hotknife", "Trailer 1", "Previon", "Coach", "Cabbie", "Stallion", "Rumpo", "RC Bandit", "Romero",
                "Packer", "Monster", "Admiral", "Squalo", "Seasparrow", "Pizzaboy", "Tram", "Trailer 2", "Turismo", "Speeder", "Reefer", "Tropic",
                "Flatbed", "Yankee", "Caddy", "Solair", "Berkley's RC Van", "Skimmer", "PCJ-600", "Faggio", "Freeway", "RC Baron", "RC Raider",
                "Glendale", "Oceanic", "Sanchez", "Sparrow", "Patriot", "Quad", "Coastguard", "Dinghy", "Hermes", "Sabre", "Rustler", "ZR-350",
                "Walton", "Regina", "Comet", "BMX", "Burrito", "Camper", "Marquis", "Baggage", "Dozer", "Maverick", "News Chopper", "Rancher",
                "FBI Rancher", "Virgo", "Greenwood", "Jetmax", "Hotring", "Sandking", "Blista Compact", "Police Maverick", "Boxville", "Benson",
                "Mesa", "RC Goblin", "Hotring Racer A", "Hotring Racer B", "Bloodring Banger", "Rancher", "Super GT", "Elegant", "Journey",
                "Bike", "Mountain Bike", "Beagle", "Cropdust", "Stunt", "Tanker", "Roadtrain", "Nebula", "Majestic", "Buccaneer", "Shamal",
                "Hydra", "FCR-900", "NRG-500", "HPV1000", "Cement Truck", "Tow Truck", "Fortune", "Cadrona", "FBI Truck", "Willard", "Forklift",
                "Tractor", "Combine", "Feltzer", "Remington", "Slamvan", "Blade", "Freight", "Streak", "Vortex", "Vincent", "Bullet", "Clover",
                "Sadler", "Firetruck LA", "Hustler", "Intruder", "Primo", "Cargobob", "Tampa", "Sunrise", "Merit", "Utility", "Nevada", "Yosemite",
                "Windsor", "Monster A", "Monster B", "Uranus", "Jester", "Sultan", "Stratum", "Elegy", "Raindance", "RC Tiger", "Flash", "Tahoma",
                "Savanna", "Bandito", "Freight Flat", "Streak Carriage", "Kart", "Mower", "Duneride", "Sweeper", "Broadway", "Tornado", "AT-400",
                "DFT-30", "Huntley", "Stafford", "BF-400", "Newsvan", "Tug", "Trailer 3", "Emperor", "Wayfarer", "Euros", "Hotdog", "Club",
                "Freight Carriage", "Trailer 3", "Andromada", "Dodo", "RC Cam", "Launch", "Police Car (LSPD)", "Police Car (SFPD)",
                "Police Car (LVPD)", "Police Ranger", "Picador", "S.W.A.T. Tank", "Alpha", "Phoenix", "Glendale", "Sadler", "Luggage Trailer A",
                "Luggage Trailer B", "Stair Trailer", "Boxville", "Farm Plow", "Utility Trailer"
            );

            if(isset($vehicleNames[$model - 400])) {
                $vehicles = $vehicleNames[$model - 400];
            }
        }
        return $vehicles;
    }

    // fetch weapon's name based on the given ID.
    function getWeaponName($weaponid) {
        $weapon_name = null;

        if(isset($weaponid)) {
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

            $weapon_name = $weapons[$weaponid];
        }
        return $weapon_name;
    }
    
    // fetch account's donator rank
    function getDonatorRank($rank) {
        $rank_name = "Not Subscribed";

        if(isset($rank)) {
            switch($rank) {
                case 1: $rank_name = "Silver Donator"; break;
                case 2: $rank_name = "Gold Donator"; break;
                case 3: $rank_name = "Platinum Donator"; break;
            }
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