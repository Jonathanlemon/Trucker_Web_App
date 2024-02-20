<?php
require_once "Database/config.php";

if(!isset($_SESSION['role'])){
    header("location: login.php");
    if($_SESSION['role'] != "admin"){
        header("location: home_page.php");
    }
}

// if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
//     header("location: home_page.php");
//     exit;
// }
 
$org_email = $org_name = $org_motto = $phone_number = $pointToDollar = "";
$email_err = $org_name_err = $org_motto_err = $phone_number_err = $pointToDollar_err = "";
// $_SESSION['org_email'] = $_SESSION['org_name'] = $_SESSION['org_motto'] = $_SESSION['phone_number'] = $_SESSION['point_to_dollar'] = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["org_email"]))){
        $email_err = "Please enter a email.";
    } elseif(!preg_match('/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/', trim($_POST["org_email"]))){
        $email_err = "Email can only contain letters, numbers, and underscores. Invalid email";
    } else {
	$temp_email = trim($_POST["org_email"]);
        $sql = "SELECT org_id FROM organizations WHERE org_email = '$temp_email'";
        
        if($stmt = mysqli_prepare($con, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            $param_email = trim($_POST["org_email"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $org_email = trim($_POST["org_email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    if(empty(trim($_POST["org_name"]))){
        $org_name_err = "Please enter your organization's name.";
    }
    else{
        $temp_org_name = trim($_POST["org_name"]);
        $sql2 = "SELECT org_id FROM organizations WHERE org_name = '$temp_org_name'";
        
        if($stmt2 = mysqli_prepare($con, $sql2)){
            mysqli_stmt_bind_param($stmt, "s", $org_name);
            
            $org_name = trim($_POST["org_name"]);
            
            if(mysqli_stmt_execute($stmt2)){
                mysqli_stmt_store_result($stmt2);
                
                if(mysqli_stmt_num_rows($stmt2) == 1){
                    $org_name_err = "This organization already exists.";
                } else{
                    $org_name = trim($_POST["org_name"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    if(empty(trim($_POST["org_motto"]))){
        $org_motto_err = "Please enter your organization's motto.";
    }
    else{
        $org_motto = trim($_POST["org_motto"]);
    }

    if(empty(trim($_POST["phone_number"]))){
        $phone_number_err = "Please enter your phone number.";
    }
    elseif(!preg_match('/^[2-9]\d{2}-\d{3}-\d{4}$/', trim($_POST["phone_number"]))){
        $phone_number_err = "Invalid phone number. Do not put parentheses around the area code, and include dashes.";
    }else{
        $phone_number = trim($_POST["phone_number"]);
    }

    if(empty(trim($_POST["point_to_dollar"]))){
        $pointToDollar_err = "Please enter your point to dollar conversion ratio.";
    }
    elseif(trim($_POST["point_to_dollar"]) == 0 || trim($_POST["point_to_dollar"]) < 0){
        $pointToDollar_err = "Your point to dollar conversion ratio cannot be 0 or negative.";
    }else{
        $pointToDollar = trim($_POST["point_to_dollar"]);
    }
    
    if(empty($org_name_err) && empty($org_motto_err) && empty($email_err) && empty($phone_number_err) && empty($pointToDollar_err)){
        
        $param_email = trim($_POST["org_email"]);
        $org_name = trim($_POST["org_name"]);
        $curr_date = date("Y-m-d");
        $default_path = "users/default/default.png";
	$_SESSION['org_email'] = $param_email;
	$_SESSION['org_name'] = $org_name;
    $_SESSION['org_motto'] = $org_motto;
    $_SESSION['phone_number'] = $phone_number;
    $_SESSION['point_to_dollar'] = $pointToDollar;
    

    $callCreateOrg = 'CALL create_org("'
    .$_SESSION['org_name'].'","'
    .$_SESSION['org_motto'].'","' 
    .$_SESSION['org_email'].'","'
    .$_SESSION['phone_number'].'","' 
    .$_SESSION['point_to_dollar']
    .'")';

    $queryStatus = mysqli_query($con, $callCreateOrg);
    if($queryStatus){
        header('location: admin_home_page.php');
    } 
    } else {
        echo "Invalid Organization Information.";
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
    <title>Create Sponsor Organization </title>
    <link rel="stylesheet" href="css/admin_home_style.css">
    <link rel="stylesheet" href="css/signup2.css" />
    
</head>

<body>
<nav class="nav_default">
         <ul class="nav_listing">
            <div class="navbox">
            <img src="../resources/Logo.png">
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
<?php echo $callCreateOrg; ?>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="form-item">
            <option>Create Sponsor Organization</a></option>
        </div>
            
	    <div class="form-item">
                <input type="text" name="org_email" id="org_email" placeholder="Email" <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $org_email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

	        <div class="form-item">
                <input type="text" name="org_name" id="org_name" placeholder="Organization Name" <?php echo (!empty($org_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $org_name; ?>">
                <span class="invalid-feedback"><?php echo $org_name_err; ?></span>

                <input type="text" name="org_motto" id="org_motto" placeholder="Organization Motto" <?php echo (!empty($org_motto_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $org_motto; ?>">
                <span class="invalid-feedback"><?php echo $org_motto_err; ?></span>
            </div>

            <div class="form-item">
                <input type="text" name="phone_number" id="phone_number" placeholder="Phone Number" <?php echo (!empty($phone_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone_number; ?>">
                <span class="invalid-feedback"><?php echo $phone_number_err; ?></span>
            </div>

            <div class="form-item">
            <input type="number" name="point_to_dollar" id="point_to_dollar" placeholder="Point to Dollar Conversion Ratio" min="1" max="1000" <?php echo (!empty($pointToDollar_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pointToDollar; ?>">
                <span class="invalid-feedback"><?php echo $pointToDollar_err; ?></span>
            </div>

            <div class="form-btns">
                <button class="signup" type="submit">Create Sponsor Organization</button>
            </div>

        </form>
    </div>





</body>
</html>