<?php
// Initialize the session
 
// Include config file
require_once "Database/config.php";

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home_page.php");
    exit;
}

function setLocked(){
    $query = "UPDATE users SET locked = 1 WHERE email = 'testing@gmail.com'";
    if(mysqli_query($con, $query)){
        echo "success";
    }else {
	echo mysqli_error();
    }

    mysqli_close($con);
}

if(!isset($_SESSION['login_gate'])){
	$_SESSION['login_attempts'] = 0;
}
$_SESSION['login_gate'] = 0;

// Define variables and initialize with empty values
$redirected = 0;
$email = $password = "";
$email_err = $password_err = $login_err = $login_attempts_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($email_err) && empty($password_err)){
        $param_email = $_POST['email'];

        $query = "SELECT email,password,user_id,role,locked, active FROM users WHERE email = '$param_email'";
        $result = mysqli_query($con, $query);
        if (is_object($result)) {
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
		if($row["locked"] === '1'){
		    header("location: reset.php");
		    $redirected = 1;
		}
        if($row['active'] == 0){
            session_destroy();
            session_start();
            header("location: login.php");
            return;
        }
                if(password_verify($password, $row['password']) && $redirected === 0){
                    session_start();    
                    mysqli_query($con, "call manual_audit(\"Login Success\", \"USER: {$param_email} logged in\")");
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $row['user_id'];
                    $_SESSION["email"] = $param_email;
                    $_SESSION["role"] = $row['role'];
                    if($_SESSION['role'] === 'driver'){
                        $result = mysqli_query($con, "SELECT org_id FROM driver_to_org where driver_id = {$_SESSION['id']}");
                        //If driver has any sponsors, set org id to first organization found
                        if($result->num_rows > 0){
                            $_SESSION['org_id'] = $result->fetch_assoc()['org_id'];
                        }                            
                        //header("location: driver_home_page.php");
                        // home_page.php should function just as well as driver_home_page.php so I changed all these... 
                        // feel free to uncomment and change back if need be.
                        header("location: home_page.php");
                    }
                    elseif($_SESSION['role'] === 'sponsor'){
                        $_SESSION['org_id'] = mysqli_query($con, "SELECT org_id FROM sponsors where sponsor_id = {$_SESSION['id']}")->fetch_assoc()['org_id'];
                        //header("location: sponsor_home_page.php");
                        header("location: home_page.php");
                    }
                    elseif($_SESSION['role'] === 'admin'){
                        //header("location: admin_home_page.php");
                        header("location: home_page.php");
                    }
                }
                else{
                    mysqli_query($con, "call manual_audit(\"Login Failure\", \"USER: {$param_email} attempted to log in\")");
		    if(isset($_SESSION["last_email"]) && $_SESSION['last_email'] === trim($_POST["email"])){
		        $_SESSION['login_attempts'] = $_SESSION['login_attempts'] + 1;
			if($_SESSION['login_attempts'] >= 3 && $_SESSION['login_attempts'] < 5){
			    $login_attempts_err = 'If you cannot remember your password, reset it below. You must reset your password after 5 failed attempts.';
			}
			if($_SESSION['login_attempts'] >= 5){
                $temp_email = $_POST['email'];
			    $query = "UPDATE users SET locked = 1 WHERE email = '$temp_email'";
    			    if(mysqli_query($con, $query)){
                        mysqli_query($con, "call manual_audit(\"Login Locked\", \"USER: {$param_email} is locked due to too many incorrect login attempts\")");
            		        $login_attempts_err = "Your account has been locked. You must reset your password.";
    			    }
    			}
		    } else{
			$_SESSION['login_attempts'] = 1;
		    }
		    $_SESSION['last_email'] = trim($_POST["email"]);
                    $login_err = "Invalid password.";
                }
            }else{
                $login_err = "Invalid email or password.";
            }
        }else{
            $login_err = "Oops... Something went wrong.";
        }                         
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

   
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

	    <p><?php echo $login_attempts_err; ?></p>

            <div class="form-item">
                <input type="text" name="email" id="email" placeholder="Enter email" <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

            <div class="form-item">
                <span class="pwd-format">
                    8-15 AlphaNumeric Characters
                </span>
                <input type="password" name="password" id="password" placeholder="Enter password" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>

            <div class="form-btns">
                <span class="invalid-feedback"><?php echo $login_err; ?></span>
                <button class="signup" type="submit">Login</button>
                <div class="options">
                    Don't have an account? <a href="signup.php">Signup here</a>
                </div>
		<div class="options">
		    Forgot your password? <a href="reset.php">Reset password</a>
                </div>

            </div>

        </form>
    





</body>

</html>
