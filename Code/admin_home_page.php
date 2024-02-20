<?php
require_once "Database/config.php";
if(!isset($_SESSION['role'])){
   header("location: login.php");
   if($_SESSION['role'] != "admin"){
      header("location: home_page.php");
   }
}

$_SESSION['remote_view'] = 0;
?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>home</title>
      <link rel="stylesheet" href="css/admin_home_style.css">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <script src="js/admin_home_page.js"></script>
   </head>
   <body>
      <nav class="nav_default">
         <ul class="nav_listing">
            <div class="navbox">
            <img src="../resources/Logo.png">
            <li><a href="home_page.php">Home</a></li>
            <li><a href="profile_page.php">Profile</a></li>
            <li><a href="html/faq.html">FAQ</a></li>
            <li><a href="reporting.php">Reporting</a></li>
            <li><a href="admin_create_accounts.php">Create Accounts</a></li>
            <li><a href="admin_modify_accounts.php">Modify Accounts</a></li>
            <li><a href="adminViewAsSponsorOrDriver.php">Admin View Homepages</a></li>
            <li><a href="logout.php">Logout</a></li>
            </div>
            
         </ul>
      </nav>

      <div class="content">
         <div class="base">
            <label class="indent">System Registry: </label>
            <div id="full_list" class="scroll_box">
               <ul id="organization_list" class="scroll_list">
                  <?php
                  $query = "SELECT * FROM organizations";
                  $result = mysqli_query($con, $query);
                  
                  for($x = 0; $x < ($result->num_rows); $x++){//For each organization
                     $row = $result->fetch_assoc();

                     $query = "SELECT driver_id FROM driver_to_org WHERE org_id=${row['org_id']}";
                     $driver_result = mysqli_query($con, $query);
                     $sponsor_result = mysqli_query($con, "SELECT * FROM sponsors where org_id=${row['org_id']}");
                     $org_name = mysqli_query($con, "SELECT org_name FROM organizations where org_id=${row['org_id']}")->fetch_assoc()['org_name'];

                     $html = "";
                     
                     $html = $html . "<li class=organization_entry>
                        <div class=\"organization_heading\">
                           <p>{$org_name}</p><p>Sponsor Users: {$sponsor_result->num_rows}</p><p>Driver Users: {$driver_result->num_rows}</p><button>Expand</button>
                        </div>
                        <div class=\"organization_body\">
                           <ul class=\"users_list\" style=\"display: none;\">
                              <div class=\"sponsor_users\">
                     ";
                     for($i = 0; $i< ($sponsor_result->num_rows); $i++){
                        $row = $sponsor_result->fetch_assoc();
                        
                        $html = $html . "
                                 <li class=\"user_entry\"><p>{$row['first_name']} {$row['last_name']}</p><p>{$row['email']}</p></li>
                        ";
                     }

                     $html = $html . "
                              </div>  
                              <div class=\"driver_users\">
                     ";

                     for($i = 0; $i< ($driver_result->num_rows); $i++){
                        $row = mysqli_query($con, "SELECT first_name, last_name, email, driver_id from drivers where driver_id = {$driver_result->fetch_assoc()['driver_id']}")->fetch_assoc();
                        $row2 = mysqli_query($con, "SELECT curr_points from driver_to_org where driver_id = {$row['driver_id']}")->fetch_assoc();
                        $html = $html . "
                                 <li class=\"user_entry\"><p>{$row['first_name']} {$row['last_name']}</p><p>{$row['email']}</p><p>Points: {$row2['curr_points']}</p></li>
                        ";
                     }
                     
                     $html = $html . "
                              </div>
                           </ul>
                        </div>
                     </li>
                     ";
                     echo("$html");

                  }

                  ?>             
               </ul>
            </div>
         </div>
      </div>
   </body>
</html>