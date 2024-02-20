<?php
// Initialize the session

// Include config file
 require_once "Database/config.php";

 if(!isset($_SESSION['role'])){
   header("location: login.php");
}
 $email = $_SESSION["email"];
 
$firstnameQuery = "SELECT first_name FROM users WHERE email = '$email'";
$result = mysqli_query($con, $firstnameQuery);
if (is_object($result)) {
   if ($result->num_rows === 1) {
      $row = $result->fetch_assoc();
      $_SESSION["firstname"] = $row['first_name'];
      if(!result){
         http_response_code(404);
         die(mysqli_error($con));
   }
   }
}
 
$lastnameQuery = "SELECT last_name FROM users WHERE email = '$email'";
 $result = mysqli_query($con, $lastnameQuery);
 if (is_object($result)) {
   if ($result->num_rows === 1) {
       $row = $result->fetch_assoc();
       $_SESSION["lastname"] = $row['last_name'];
       if(!result){
         http_response_code(404);
         die(mysqli_error($con));
     }
   }
}

$roleQuery = "SELECT role FROM users WHERE email = '$email'";
 $result = mysqli_query($con, $roleQuery);
 if (is_object($result)) {
   if ($result->num_rows === 1) {
       $row = $result->fetch_assoc();
       $_SESSION["role"] = $row['role'];
       if(!result){
         http_response_code(404);
         die(mysqli_error($con));
     }
   }
}

$activeQuery = "SELECT active FROM users WHERE email = '$email'";
 $result = mysqli_query($con, $activeQuery);
 if (is_object($result)) {
   if ($result->num_rows === 1) {
       $row = $result->fetch_assoc();
       $_SESSION["active"] = $row['active'];
       if(!result){
         http_response_code(404);
         die(mysqli_error($con));
     }
   }
}

$security1QuestionQuery = "SELECT security_question1 FROM users WHERE email = '$email'";
 $result = mysqli_query($con, $security1QuestionQuery);
 if (is_object($result)) {
   if ($result->num_rows === 1) {
       $row = $result->fetch_assoc();
       $_SESSION["security_question1"] = $row['security_question1'];
       if(!result){
         http_response_code(404);
         die(mysqli_error($con));
     }
   }
}

$security1AnswerQuery = "SELECT security_answer1 FROM users WHERE email = '$email'";
 $result = mysqli_query($con, $security1AnswerQuery);
 if (is_object($result)) {
   if ($result->num_rows === 1) {
       $row = $result->fetch_assoc();
       $_SESSION["security_answer1"] = $row['security_answer1'];
       if(!result){
         http_response_code(404);
         die(mysqli_error($con));
     }
   }
}

$security2QuestionQuery = "SELECT security_question2 FROM users WHERE email = '$email'";
 $result = mysqli_query($con, $security2QuestionQuery);
 if (is_object($result)) {
   if ($result->num_rows === 1) {
       $row = $result->fetch_assoc();
       $_SESSION["security_question2"] = $row['security_question2'];
       if(!result){
         http_response_code(404);
         die(mysqli_error($con));
     }
   }
}

$security2AnswerQuery = "SELECT security_answer2 FROM users WHERE email = '$email'";
 $result = mysqli_query($con, $security2AnswerQuery);
 if (is_object($result)) {
   if ($result->num_rows === 1) {
       $row = $result->fetch_assoc();
       $_SESSION["security_answer2"] = $row['security_answer2'];
       if(!result){
         http_response_code(404);
         die(mysqli_error($con));
     }
   }
}

$security3QuestionQuery = "SELECT security_question3 FROM users WHERE email = '$email'";
 $result = mysqli_query($con, $security3QuestionQuery);
 if (is_object($result)) {
   if ($result->num_rows === 1) {
       $row = $result->fetch_assoc();
       $_SESSION["security_question3"] = $row['security_question3'];
       if(!result){
         http_response_code(404);
         die(mysqli_error($con));
     }
   }
}

$security3AnswerQuery = "SELECT security_answer3 FROM users WHERE email = '$email'";
 $result = mysqli_query($con, $security3AnswerQuery);
 if (is_object($result)) {
   if ($result->num_rows === 1) {
       $row = $result->fetch_assoc();
       $_SESSION["security_answer3"] = $row['security_answer3'];
       if(!result){
         http_response_code(404);
         die(mysqli_error($con));
     }
   }
}

$notification_count = "SELECT * FROM messages WHERE recipient = '$email'";
 $result = mysqli_query($con, $notification_count);
 if (is_object($result)) {
   $_SESSION['notification_count'] = $result->num_rows;

   if ($result->num_rows == 1) {
       $row = $result->fetch_assoc();
       $_SESSION["notification_count"] = $row['notification_count'];
       if(!result){
         http_response_code(404);
         die(mysqli_error($con));
     }
   }

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
      <div class="card">
         <form action="/update_profile_page.php" method=post>
         <label class="indent">First Name </label><p class="card_detail">
            <input type="text" name="txtFirstName" id="txtFirstName" value="<?php echo $_SESSION["firstname"]?>">
         </p>
         <label class="indent">Last Name </label><p class="card_detail">
            <input type="text" name="txtLastName" id="txtLastName" value="<?php echo $_SESSION["lastname"]?>">
         </p>
         <label class="indent">Role </label><p class="card_detail">
            <input type="text" name="txtRole" id="txtRole" value="<?php echo $_SESSION["role"];?>" readonly>
         </p>
         </br>
         <label class="indent">Active </label><p class="card_detail">
            <input type="text" name="txtRole" id="txtRole" value="<?php echo $_SESSION["active"] ? "yes" : "no";?>" readonly>
         </p>
         <label class="indent">Email </label><p class="card_detail"> 
            <input type="text" name="txtEmail" id="txtEmail" value="<?php echo $_SESSION["email"];?>" readonly>
         </p>
         <label class="indent">Security Question #1 </label><p class="card_detail">
            <input type="text" name="txtSecurity1" id="txtSecurity1" value="<?php echo $_SESSION["security_question1"];?>" readonly>
         </p>
         <label class="indent">Security Answer #1 </label><p class="card_detail">
            <input type="text" name="txtAnswer1" id="txtAnswer1" value="<?php echo $_SESSION["security_answer1"]?>">
         </p>
         </br>
         <label class="indent">Security Question #2 </label><p class="card_detail">
            <input type="text" name="txtSecurity2" id="txtSecurity2" value="<?php echo $_SESSION["security_question2"];?>" readonly>
         </p>
         <label class="indent">Security Answer #2 </label><p class="card_detail">
            <input type="text" name="txtAnswer2" id="txtAnswer2" value="<?php echo $_SESSION["security_answer2"]?>">
         </p>
         <label class="indent">Security Question #3 </label><p class="card_detail">
            <input type="text" name="txtSecurity3" id="txtSecurity3" value="<?php echo $_SESSION["security_question3"];?>" readonly>
         </p>
         <label class="indent">Security Answer #3 </label><p class="card_detail">
            <input type="text" name="txtAnswer3" id="txtAnswer3" value="<?php echo $_SESSION["security_answer3"]?>">
         </p>
         </br>
         <input type="submit" name="submit" value="Submit">
         </form>
      </div>
   </body>
   