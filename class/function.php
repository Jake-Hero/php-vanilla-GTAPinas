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
                echo "
                <div class='alert alert-success' id='message'>
                    <strong>$subject</strong> 
                    <div>$message</div>
                </div>
                ";
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
        
        $name = null;

        if ($result->rowCount() > 0) {
            $name = $result->fetchColumn();
        }

        return $name;
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