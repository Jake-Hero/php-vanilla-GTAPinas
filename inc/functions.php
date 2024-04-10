<?php
function checkLoggedIn($redirect = true)
{
    if(isset($_SESSION['UID']))
    {
        if($redirect)
        {
            header("Location: index.php?page=dashboard");
            die;
        }
        else
        {
            return false;
        }
    }
    return true;
}
function isLoggedIn($redirect = true)
{
    if(!isset($_SESSION['UID']))
    {
        if($redirect)
        {
            header("Location: index.php?page=login");
            die;
        }
        else
        {
            return false;
        }
    }
    return true;
}

function GetMessage($subject, $message, $type)
{
	switch($type)
	{
		case 1: // Error
		{
			echo "
			<div class='alert alert-danger' id='message'>
				<strong>$subject</strong> $message
			</div>
			";
		} break;
		case 2: // Warning
		{
			echo "
			<div class='alert alert-warning' id='message'>
				<strong>$subject</strong> $message
			</div>
			";
		} break;
		case 3: // Success
		{
			echo "
			<div class='alert alert-success' id='message'>
				<strong>$subject</strong> $message
			</div>
			";
		} break;
		case 4: // Error (without fadeout)
		{
			echo "
			<div class='alert alert-danger'>
				<strong>$subject</strong> $message
			</div>
			";
		} break;
		case 5: // Warning (without fadeout)
		{
			echo "
			<div class='alert alert-warning'>
				<strong>$subject</strong> $message
			</div>
			";
		} break;
		case 6: // Success (without fadeout)
		{
			echo "
			<div class='alert alert-success'>
				<strong>$subject</strong> $message
			</div>
			";
		} break;
	}
}


function UserLogin()
{
    global $pdo;
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = $pdo->prepare("SELECT * FROM accounts WHERE email = :email OR username = :email");
        $query->execute(array(':email' => $email));

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                // Verify password using bcrypt
                if (password_verify($password, $row['password'])) {
                    $_SESSION['UID'] = $row['id'];
                    $_SESSION['USER'] = $row;
                    echo '<script>window.location=\'?page=dashboard\'</script>';
                    return; // Exit function after successful login
                } else {
                    GetMessage("Oops!", "Looks like your login credentials are incorrect. Please try again!", 1);
                    return; // Exit function if password doesn't match
                }
            }
        } else {
            GetMessage("Oops!", "Your account does not exist in our database. Please try again later!", 1);
            return; // Exit function if account doesn't exist
        }
    } else {
        GetMessage("Oh snap!", "Make sure you fill out all the fields below. Please try again!", 1);
        return; // Exit function if fields are empty
    }
}

?>