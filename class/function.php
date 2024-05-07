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
    public function getCharacterData($id) {
        $result = $this->pdo->prepare("SELECT * FROM characters WHERE id = :id LIMIT 1;");
        $result->execute(array(':id' => $id));
        
        if ($result->rowCount() > 0) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return null; // No data found
        }
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

    function throw404() {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        include __DIR__ . "/../404.php";
        return die();
    }
}

?>