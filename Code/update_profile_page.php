<?php
require_once "Database/config.php";

if(!isset($_SESSION['role'])){
   header("location: login.php");
}

$old_first_name = $_SESSION["firstname"];
$old_last_name = $_SESSION["lastname"];
$old_security_answer1 = $_SESSION["security_answer1"];
$old_security_answer2 = $_SESSION["security_answer2"];
$old_security_answer3 = $_SESSION["security_answer3"];
$new_first_name = $new_last_name = "";
$new_security_answer1 = $new_security_answer2 = $new_security_answer3 = "";
$email = $_SESSION["email"];
$firstname_err = $lastname_err = $answer1_err = $answer2_err = $answer3_err= "";
   if(empty(trim($_POST["txtFirstName"]))){
      $firstname_err = "Please enter your first name.";
   }
   else{
      $new_first_name = trim($_POST["txtFirstName"]);
   }

   if(empty(trim($_POST["txtLastName"]))){
      $lastname_err = "Please enter your last name.";
   }
   else{
      $new_last_name = trim($_POST["txtLastName"]);
   }

   if(empty(trim($_POST["txtAnswer1"]))){
      $answer1_err = "Please enter your security answer#1.";
   }
   else{
      $new_security_answer1 = trim($_POST["txtAnswer1"]);
   }

   if(empty(trim($_POST["txtAnswer2"]))){
      $answer2_err = "Please enter your security answer#2.";
   }
   else{
      $new_security_answer2 = trim($_POST["txtAnswer2"]);
   }

   if(empty(trim($_POST["txtAnswer3"]))){
      $answer3_err = "Please enter your security answer#3.";
   }
   else{
      $new_security_answer3 = trim($_POST["txtAnswer3"]);
   }

   if (empty($firstname_err) && empty($lastname_err) && empty($answer1_err) && empty($answer2_err) && empty($answer3_err))
   {
      $_SESSION['first_name'] = $_POST['txtFirstName'];
      $_SESSION['last_name'] = $_POST['txtLastName'];
      $_SESSION['security_answer1'] = $_POST['txtAnswer1'];
      $_SESSION['security_answer2'] = $_POST['txtAnswer2'];
      $_SESSION['security_answer3'] = $_POST['txtAnswer3'];
      // $new_first_name = $_POST["txtFirstName"];
      // $new_last_name = $_POST["txtLastName"];
      // $new_security_answer = $_POST["txtAnswer1"];
      // $_SESSION["first_name"] = $new_first_name;
      // $_SESSION["last_name"] = $new_last_name;
      
      
      $sql = "UPDATE users SET first_name='$new_first_name', last_name='$new_last_name', security_answer1='$new_security_answer1', security_answer2='$new_security_answer2', security_answer3='$new_security_answer3' where email='$email'";
      // echo $new_first_name."\n";
      // echo $new_last_name."\n";  
      if (mysqli_query($con, $sql) === TRUE) {
         mysqli_query($con, "CALL manual_audit('Profile Update', 'User {$_SESSION['email']} updated their profile information')");
         echo("Profile updated successfully.");
      
      } else {
         echo "Error: Your first name, last name, or security question answers was blank. Please press the back arrow, and then refresh the profile_page.php page as none of your profile information was updated.";
      }
   }
      else {
         echo "Error: Your first name, last name, or security question answer was blank. Please press the back arrow, and then refresh the profile_page.php page as none of your profile information was updated.";
      http_response_code(404);
      die(mysqli_error($con));
   }


   mysqli_close($con); 
?>
<!DOCTYPE html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>profile</title>
      <link rel="stylesheet" href="css/profile_style.css">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
   </head>
   <body>
      <nav class="nav_default">
         <ul class="nav nav_listing">
            <div class="image"><img src="resources/Logo.png">
            <li><a href="home_page.php">Home</a></li>
            <li><a href="profile_page.php">Profile</a></li>
            <li><a href="faq.html">FAQ</a></li>
            <li><a href="catalog.php">Catalog</a></li>
            <li><a href="logout.php">Logout</a></li>
	         <!-- <li> -->
               <button type = "button" class="icon-button">
                  <span class = "material-icons">notifications</span>
                  <span class = "icon-button__badge">2</span>
               </button>
            <!-- </li> -->
            </div>
         </ul>
      </nav>
      <div class="card">
         <form action="/update_profile_page.php" method=post>
         <label class="indent">First Name </label><p class="card_detail">
                <input type="text" name="txtFirstName" id="txtFirstName" <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_first_name; ?>">
                <span class="invalid-feedback"><?php echo $firstname_err; ?></span>
        </p>
         
         <label class="indent">Last Name </label><p class="card_detail">
                <input type="text" name="txtLastName" id="txtLastName" <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_last_name; ?>">
                <span class="invalid-feedback"><?php echo $lastname_err; ?></span>
         </p>
         <label class="indent">Role </label><p class="card_detail">
            <input type="text" name="txtRole" id="txtRole" value="<?php echo($_SESSION["role"]);?>" readonly>
         </p>
         
         <label class="indent">Active </label><p class="card_detail">
            <input type="text" name="txtRole" id="txtRole" value="<?php echo($_SESSION["active"] ? "yes" : "no");?>" readonly>
         </p>
         </br>
         <label class="indent">Email </label><p class="card_detail"> 
            <input type="text" name="txtEmail" id="txtEmail" value="<?php echo($_SESSION["email"]);?>" readonly>
         </p>
         <label class="indent">Security Question #1 </label><p class="card_detail">
            <input type="text" name="txtSecurity1" id="txtSecurity1" value="<?php echo($_SESSION["security_question1"]);?>" readonly>
         </p>
         <label class="indent">Security Answer #1 </label><p class="card_detail">
            <input type="text" name="txtAnswer1" id="txtAnswer1" <?php echo((!empty($answer1_err)) ? 'is-invalid' : ''); ?>" value="<?php echo $new_security_answer1; ?>">
            <span class="invalid-feedback"><?php echo $answer1_err; ?></span>
         </p>
         </br>
         <label class="indent">Security Question #2 </label><p class="card_detail">
            <input type="text" name="txtSecurity2" id="txtSecurity2" value="<?php echo($_SESSION["security_question2"]);?>" readonly>
         </p>
         <label class="indent">Security Answer #2 </label><p class="card_detail">
            <input type="text" name="txtAnswer2" id="txtAnswer2" <?php echo((!empty($answer2_err)) ? 'is-invalid' : ''); ?>" value="<?php echo $new_security_answer2; ?>">
            <span class="invalid-feedback"><?php echo $answer2_err; ?></span>
         </p>
         <label class="indent">Security Question #3 </label><p class="card_detail">
            <input type="text" name="txtSecurity3" id="txtSecurity3" value="<?php echo($_SESSION["security_question3"]);?>" readonly>
         </p>
         <label class="indent">Security Answer #3 </label><p class="card_detail">
            <input type="text" name="txtAnswer3" id="txtAnswer3" <?php echo((!empty($answer3_err)) ? 'is-invalid' : ''); ?>" value="<?php echo $new_security_answer3; ?>">
            <span class="invalid-feedback"><?php echo($answer3_err); ?></span>
         </p>
</br>
         <input type="submit" name="submit" value="Submit">
         </form>
      </div>
   </body>
