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