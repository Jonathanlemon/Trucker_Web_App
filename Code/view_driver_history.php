<?php
require_once "Database/config.php";
if(!isset($_SESSION['role'])){
   header("location: login.php");
   if($_SESSION['role'] == "driver"){
      header("location: home_page.php");
   }
}

$selected_user = -1;

if($_SERVER['REQUEST_METHOD'] == "POST"){
   $selected_user = $_POST['user_id'];
}

?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>History</title>
      <link rel="stylesheet" href="css/admin_home_style.css">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
   </head>
   <body>
      <nav class="nav_default">
         <ul class="nav_listing">
            <div class="navbox">
            <img src="../resources/Logo.png">
            <li><a href="home_page.php">Home</a></li>
            <li><a href="profile_page.php">Profile</a></li>
            <li><a href="html/faq.html">FAQ</a></li>
            <li><a href="logout.php">Logout</a></li>
            </div>
            
         </ul>
      </nav>

      <div class="content">
         <div class="base">
            <label class="indent">History for: <?php $user_email = mysqli_query($con, "SELECT email from users where user_id = '{$selected_user}'")->fetch_assoc()["email"];echo("{$user_email}")?></label>
            <div id="full_list" class="scroll_box">
               <ul id="user_list" class="scroll_list">
                  <?php
                  $query = "SELECT * FROM points where driver_id = '{$selected_user}' and org_id = '{$_SESSION['org_id']}'";
                  $result = mysqli_query($con, $query);
                  for($x = 0;$x < $result->num_rows;$x++){
                     $row = $result->fetch_assoc();
                     echo("<li class=\"user_entry\"><p>Point Change: {$row['amt_change']}  {$row['description']}  {$row['time_stamp']}</p></li>");
                  }
                  ?>             
               </ul>
            </div>
      </div>
   </body>
</html>