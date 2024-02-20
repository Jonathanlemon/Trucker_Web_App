<?php
require_once "Database/config.php";

if(!isset($_SESSION['role'])){
   header("location: login.php");
   if($_SESSION['role'] == "driver"){
      header("location: home_page.php");
   }
   $_SESSION["remote_view"] = 0;
}

$result = mysqli_query($con, "SELECT org_name, org_desc from organizations where org_id = ${_SESSION['org_id']}")->fetch_assoc();
$org_name = $result['org_name'];
$org_desc = $result['org_desc'];
$temp_org_id = $_SESSION['org_id'];

$query = "SELECT pointToDollar FROM organizations WHERE org_id = '$temp_org_id'";
$result = mysqli_query($con, $query);

if(is_object($result)){
	if($result->num_rows === 1){
		$row = $result->fetch_assoc();
		$pointRate = $row['pointToDollar'];

	}
}

// Need to connect php to front end so that radio buttons can modify the php variable for displaying currently selected driver name for modification
$selected_driver = -1;
if($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['pointRate'])){
   $selected_driver = $_POST['driver_id'];
}
else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pointRate'])){
   //do some shiz
   $pointRate = $_POST['pointRate'];
   $query = "UPDATE organizations SET pointToDollar = '$pointRate' WHERE org_id = '$temp_org_id'";
   if(!mysqli_query($con, $query)){
	echo "error";
   }
}

?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Home</title>
      <link rel="stylesheet" href="css/sponsor_home_style.css">
      <script src="js/sponsor_home_page.js"></script>
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
            <li><a href="catalog.php">Catalog</a></li>
            <li><a href="create_sponsor.php">Create Accounts</a></li>
            <li><a href="modify_org_info.php">Modify Organization</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li>
                  <ul style="margin-left:30%;">
                     <li>Points to dollars: <?php echo $pointRate; ?> </li>
                     <li>
                        <form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post">
                     <input type='text' name='pointRate' id='pointRate' placeHolder='Change rate'></input>
                     <input type='hidden' id='confirm_flag' name='confirm_flag'/>
                        </form>	    
                     </li>        
                        </ul>
            </li>
            </div>
         </ul>
      </nav>


      <h1>My Organization: <?php echo("$org_name");?></h1>
      <h2><?php echo("$org_desc");?></h2>
      <h2><a href="driver_home_page.php">View As Driver</a></h2>


      <div class="content">
         <div class="base">
            <label class="indent">My Drivers: </label>
               <div id="drivers" class="scroll_box">
                  <ul id="drivers_list" class="scroll_list">
                     <form action="sponsor_home_page.php" id="select_driver_form" method="POST">
                        <?php
                              $query = "SELECT * FROM driver_to_org WHERE org_id = {$_SESSION['org_id']}";
                              $result = mysqli_query($con, $query);

                              for($x = 0; $x < ($result->num_rows); $x++){
                                 $row = $result->fetch_assoc();
                                 $person = mysqli_query($con, "SELECT * FROM drivers WHERE driver_id={$row['driver_id']}")->fetch_assoc();
                                 
                                 $checked_val = "";
                                 if($person['driver_id'] == $selected_driver){
                                    $checked_val = "checked";
                                 }

                                 echo("
                                 <li class=driver_entry>
                                    <p>{$person['first_name']} {$person['last_name']}</p>
                                    <p>{$person['email']}</p>
                                    <p>Points: {$row['curr_points']}</p>
                                    <input class=\"driver_radio\" type=\"radio\" name=\"driver_id\" onclick=\"driver_select()\" value=\"{$person['driver_id']}\" $checked_val>
                                 </li>
                                 ");
                              }
                           ?>
                     </form>  
                  </ul>
               </div>

            <form action="manage_user.php" method="POST" id="manage_driver_form">
            <input type="number" name="user_id" id="driver_id_holder2" style="display:none;" value="<?php echo("$selected_driver"); ?>"><br>
            </form>
            <button id="manage_driver_btn" onclick="manage_driver()">Manage Driver</button>
            <button id="point_change_btn" onclick="toggle_point_panel()">Change Points</button>
            <button id="history_btn" onclick="view_hist()">View History</button>
         </div>

      


         <div class="base">
            <label class="indent">Applications: </label>
            <div id="applications" class="scroll_box">
               <ul id="applications_list" class="scroll_list">

               <?php
                  require_once "Database/config.php";

                  $query = "SELECT * FROM applications WHERE org_id = {$_SESSION['org_id']}";
                  $result = mysqli_query($con, $query);

                  for($x = 0; $x < ($result->num_rows); $x++){
                     $row = $result->fetch_assoc();
                     $person = mysqli_query($con, "SELECT * FROM drivers WHERE driver_id = {$row['driver_id']}")->fetch_assoc();
                     echo("
                        <li class=application_entry>
                           <p>${person['first_name']} ${person['last_name']}</p>
                           <p>${person['email']}</p>
                           <form action=\"application_response.php\" method='POST'>
                              <input type=\"text\" name=\"app_id\" value=\"${row['application_id']}\" style=\"display:none;\">
                              <input type=\"radio\" name=\"choice\" id=\"accept\" value=\"1\">
                              <label for=\"accept\">Accept</label>
                              <input type=\"radio\" name=\"choice\" id=\"reject\" value=\"0\">
                              <label for=\"reject\">Reject</label>
                              <input type=\"submit\" value=\"Submit\">
                           </form>
                        </li>
                     ");
                  }
                  ?>

               </ul>
            </div>
         </div>


         <?php
            if($selected_driver != -1){
               $person = mysqli_query($con, "SELECT * FROM drivers WHERE driver_id={$selected_driver}")->fetch_assoc();
               echo("
               <div class=\"base\" id=\"manage_driver_panel\" style=\"display: none;\">
                  <label class=\"indent\">Manage Driver: </label>
                     <div class=\"scroll_box\">
                        <form action=\"point_change.php\" method=\"POST\">
                           <p>{$person['first_name']} {$person['last_name']}</p>
                           <p>Enter point change:</p>
                           <input type=\"number\" name=\"point_change\" placeholder=\"100\">
                           <p>Enter description:</p>
                           <textarea name=\"reason\" rows=\"4\" cols=\"50\" maxlength=\"200\" style=\"resize: none;\"></textarea>
                           <input type=\"number\" name=\"driver_id\" id=\"driver_id_holder\" style=\"display:none;\" value=\"$selected_driver\"><br>
                           <input type=\"submit\" value=\"Submit\">
                        </form>
                     </div>
               </div>
               ");
            }
         ?>
      </div>     
   </body>
</html>