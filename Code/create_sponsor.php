<?php
require_once "Database/config.php";

if(!isset($_SESSION['role'])){
    header("location: login.php");
    if($_SESSION['role'] == "driver"){
        header("location: home_page.php");
    }
}
 
$email = $password = $confirm_password = $firstname = $lastname = "";
$email_err = $password_err = $confirm_password_err = $firstname_err = $lastname_err = "";
echo $_SESSION['sec_role'];

$sql2="SELECT org_name, org_id FROM organizations where 1";
$result=mysqli_query($con, $sql2);

while ($row= mysqli_fetch_assoc($result)) {
    $org_name=$row["org_name"];
    $options.="<OPTION VALUE=\"{$row['org_id']}\">".$org_name.'</option>';
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } elseif(!preg_match('/^[a-zA-Z0-9_@.]+$/', trim($_POST["email"]))){
        $email_err = "Email can only contain letters, numbers, and underscores.";
    } else{
	$temp_email = trim($_POST["email"]);
        $sql = "SELECT user_id FROM users WHERE email = '$temp_email'";
        
        if($stmt = mysqli_prepare($con, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            $param_email = trim($_POST["email"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $password_err = "Password must have at least 8 characters.";
    } elseif(!preg_match('/[A-Z]/', trim($_POST["password"]))){
        $password_err = "Password must contain at least one uppercase letter.";
    } elseif(!preg_match('/[a-z]/', trim($_POST["password"]))){
        $password_err = "Password must contain at least one lowercase letter.";
    } elseif(!preg_match('/[0-9]/', trim($_POST["password"]))){
        $password_err = "Password must contain at least one number.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    if(empty(trim($_POST["firstname"]))){
        $firstname_err = "Please enter your first name.";
    }
    else{
        $firstname = trim($_POST["firstname"]);
    }

    if(empty(trim($_POST["lastname"]))){
        $lastname_err = "Please enter your last name.";
    }
    else{
        $lastname = trim($_POST["lastname"]);
    }
    
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($firstname_err) && empty($lastname_err)){
        
        $param_email = trim($_POST["email"]);
        $param_password = password_hash($password, PASSWORD_DEFAULT);
        $curr_date = date("Y-m-d");
        $default_path = "users/default/default.png";
	$_SESSION['sec_email'] = $param_email;
	$_SESSION['sec_password'] = $param_password;
	$_SESSION['sec_role'] = 'sponsor';
    $_SESSION['new_user_org'] = $_POST['org'];
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    header("location: set_security.php");
    
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>profile</title>
        <link rel="stylesheet" href="css/signup2.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
        rel="stylesheet">
        <link rel="stylesheet" href="css/profile_style.css">
</head>

<body>
<nav class="nav_default">
         <ul class="nav nav_listing">
            <div class="image"><img src="resources/Logo.png">
            <?php
               if($_SESSION['role'] == 'driver'){
                  echo "
                     <li><a href='home_page.php'>Home</a></li>
                     <li><a href='profile_page.php'>Profile</a></li>
                     <li><a href='html/faq.html'>FAQ</a></li>
                     <li><a href='driver_applications_page.php'>Apply</a></li>
                     <li><a href='wishlist.php'>Wishlist</a></li>
                     <li><a href='orders.php'>Orders</a></li>
                     <li><a href='logout.php'>Logout</a></li>
                  ";
               }elseif($_SESSION['role'] == 'sponsor'){
                  echo "
                     <li><a href='home_page.php'>Home</a></li>
                     <li><a href='profile_page.php'>Profile</a></li>
                     <li><a href='html/faq.html'>FAQ</a></li>
                     <li><a href='reporting.php'>Reporting</a></li>
                     <li><a href='catalog.php'>Catalog</a></li>
                     <li><a href='create_sponsor.php'>Create Accounts</a></li>
                     <li><a href='modify_org_info.php'>Modify Organization</a></li>
                     <li><a href='logout.php'>Logout</a></li>
                  ";
               }elseif($_SESSION['role'] == 'admin'){
                  echo "
                     <li><a href='home_page.php'>Home</a></li>
                     <li><a href='profile_page.php'>Profile</a></li>
                     <li><a href='html/faq.html'>FAQ</a></li>
                     <li><a href='reporting.php'>Reporting</a></li>
                     <li><a href='admin_create_accounts.php'>Create Accounts</a></li>
                     <li><a href='admin_modify_accounts.php'>Modify Accounts</a></li>
                     <li><a href='adminViewAsSponsorOrDriver.php'>Admin View Homepages</a></li>
                     <li><a href='logout.php'>Logout</a></li>
                  ";
               }
            ?>
            
            </div>
         </ul>
      </nav>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="form-item">
            <option>Create Sponsor</a></option>
        </div>
            
	    <div class="form-item">
                <input type="email" name="email" id="email" placeholder="Email" <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

        <div class="form-item">
        
        <?php
        if($_SESSION['role'] == "admin"){
            echo("<select name=\"org\" id=\"org\">
            <option value=\"0\">Select Organization </option>
            $options</select>");
        }
        if($_SESSION['role'] == "sponsor"){
            $o_name = mysqli_query($con, "SELECT org_name from organizations where org_id = '{$_SESSION['org_id']}'")->fetch_assoc()["org_name"];
            echo("<select name=\"org\" id=\"org\">
            <option value=\"{$_SESSION['org_id']}\">$o_name</option></select>");
        }
        ?>
        
        </div>

	        <div class="form-item">
                <input type="firstname" name="firstname" id="firstname" placeholder="First name" <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>">
                <span class="invalid-feedback"><?php echo $firstname_err; ?></span>

		        <input type="lastname" name="lastname" id="lastname" placeholder="Last name" <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>">
                <span class="invalid-feedback"><?php echo $lastname_err; ?></span>
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
                <button class="signup" type="submit">Create Sponsor User</button>
            </div>

        </form>
    </div>
</body>
</html>