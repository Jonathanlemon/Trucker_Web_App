<?php
require_once "Database/config.php";

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home_page.php");
    exit;
}
$user_id = "";
$email = $password = $confirm_password = $firstname = $lastname = $msg_sender = $msg_type = $content = "";
$email_err = $password_err = $confirm_password_err = $firstname_err = $lastname_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
	$temp_email = trim($_POST["email"]);

        $query = "SELECT user_id FROM users WHERE email = '$temp_email'";
        $result = mysqli_query($con, $query);

        if(is_object($result)){
            if($result->num_rows === 1){
		$row = $result->fetch_assoc();
		$user_id = $row['user_id'];
            } else{
		$email_err = "Incorrect email";
            }
	}
        else{
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }

    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a new password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $password_err = "New password must have atleast 8 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm your new password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
	$_SESSION['user_id'] = $user_id;
	$_SESSION['new_password_hash'] = password_hash($password, PASSWORD_DEFAULT);
	$_SESSION['reset_email'] = $_POST['email'];
	header("location: security.php");
    }
    
    mysqli_close($con);
}
?>
 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page </title>
    <link rel="stylesheet" href="css/signup.css" />
</head>

<body>

    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            
	    <div class="form-item">
                <input type="email" name="email" id="email" placeholder="Email" <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

            <div class="form-item">
                <!-- add a password format display -->
                <span class="pwd-format">
                    8-15 AlphaNumeric Characters
                </span>
                <input type="password" name="password" id="password" placeholder="Password" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-btns">
                <button class="signup" type="submit">Reset Password</button>
                <div class="options">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </div>

        </form>
    </div>





</body>

</html>