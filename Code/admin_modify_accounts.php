<?php
require_once "Database/config.php";
$selected_user = -1;

if(!isset($_SESSION['role'])){
   header("location: login.php");
   if($_SESSION['role'] != "admin"){
       header("location: home_page.php");
   }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
   $selected_user = $_POST['user_id'];
}

?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>home</title>
      <link rel="stylesheet" href="css/admin_home_style.css">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <script>

         function manage_user_submit(){
            if(document.getElementById().value != -1){
               document.forms["manage_user_form"].submit();
            }
         }

         function user_select(){
            document.forms["select_user_form"].submit();
         }

      </script>
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

      <div class="content">
         <div class="base">
            <label class="indent">User List: </label>
            <div id="full_list" class="scroll_box">
               <form action="admin_modify_accounts.php" method="POST" id="select_user_form">

               <ul id="user_list" class="scroll_list">
                  <?php
                  $query = "SELECT * FROM users";
                  $result = mysqli_query($con, $query);
                  for($x = 0;$x < $result->num_rows;$x++){
                     $row = $result->fetch_assoc();
                     $checked = "";
                     if($selected_user == $row["user_id"]){
                        $checked = "checked";
                     }
                     echo("<li class=\"user_entry\"><p>{$row['first_name']} {$row['last_name']}</p><p>{$row['email']}</p><p>Role: {$row['role']}</p><input type=\"radio\" value=\"{$row['user_id']}\" onclick=\"user_select()\" name=\"user_id\" $checked></li>");
                  }
                  ?>             
               </ul>
               </form>
            </div>
            <form action="manage_user.php" method="POST" id="manage_user_form">
               <input type="number" name="user_id" id="driver_id_holder2" style="display:none;" value="<?php echo("$selected_user"); ?>"><br>
               <button onclick="manage_user_submit()">Manage User</button>
            </form>
         </div>
      </div>
   </body>
</html>