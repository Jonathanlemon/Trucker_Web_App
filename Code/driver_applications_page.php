<?php 
require_once("Database/config.php");
if(!isset($_SESSION['role'])){
   header("location: login.php");
}

if($_SESSION['role'] != "driver"){
   header("home_page.php");
}

?>
<!DOCTYPE html>
<html>
   <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Apply to Organizations</title>
   <link rel="stylesheet" href="css/driver_applications_style.css">
   <script src="js/driver_applications_page.js"></script>
</head>
<body>
   <nav class="nav_default">
      <ul class="nav_listing">
         <div class="navbox">
            <img src="resources/Logo.png">
            <li><a href="home_page.php">Home</a></li>
            <li><a href="profile_page.php">Profile</a></li>
            <li><a href="html/faq.html">FAQ</a></li>
            <li><a href="driver_applications_page.php">Apply</a></li>
            <li><a href="wishlist.php">Wishlist</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
            
            
         </div>
      </ul>
   </nav>





   <div class="content">

      <div class="base">
         <label class="indent" id="apps">Organizations: </label>
         <div id="app_scroll_box" class="scroll_box">
            <ul id="app_listing" class="scroll_list">
               
               <?php
               require_once "Database/config.php";
               $query = "SELECT * FROM organizations";
               $result = mysqli_query($con, $query);

               $query = "SELECT * FROM applications WHERE driver_id=${_SESSION['id']}";
               $result2 = mysqli_query($con, $query);

               $query = "SELECT * FROM driver_to_org WHERE driver_id=${_SESSION['id']}";
               $result3 = mysqli_query($con, $query);

               $invalidOrgs=[];

               for($x = 0; $x < ($result2->num_rows); $x++){
                  $row = $result2->fetch_assoc();
                  array_push($invalidOrgs, $row['org_id']);
               }

               for($x = 0; $x < ($result3->num_rows); $x++){
                  $row = $result3->fetch_assoc();
                  array_push($invalidOrgs, $row['org_id']);
               }

               for($x = 0; $x < ($result->num_rows); $x++){
                  $row = $result->fetch_assoc();
                  $disabledFlag = '';
                  if(in_array($row['org_id'], $invalidOrgs)){
                     $disabledFlag = 'disabled';
                  }
                  echo("
                  <li class=org_entry>
                     <p>${row['org_name']}</p>
                     <p>${row['org_desc']}</p>
                     <form action=\"apply.php\" method='POST'>
                     <input type=\"number\" name=\"org_id\" value=\"${row['org_id']}\" style=\"display:none;\">
                     <input type=\"number\" name=\"driver_id\" value=\"${_SESSION['id']}\" style=\"display:none;\">
                     <input type=\"submit\" value=\"Apply\" $disabledFlag>
                     </form>
                  </li>
                  ");
               }
               ?>

            </ul>
         </div>
      </div>




   </div>
</body>
</html>

