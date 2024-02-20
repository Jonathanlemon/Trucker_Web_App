<?php
require_once "Database/config.php";

if(!isset($_SESSION['role'])){
   header("location: login.php");
}

$org_name = "No Organization";
$org_desc = "";
$points = 0;


$conversionRate = 0;

if(isset($_SESSION['org_id'])){
   $query = "SELECT * FROM organizations WHERE org_id = {$_SESSION['org_id']}";
   $result = mysqli_query($con, $query);
   $conversionRate = 100.00;
   if(is_object($result)){
      $row = $result->fetch_assoc();
      $conversionRate = $row['pointToDollar'];
   }
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_from_wishlist'])){
   $temp_prod_id = $_POST['id'];
   $temp_user_id = $_SESSION['id'];
   $query = "DELETE FROM wishlist WHERE product_id = '$temp_prod_id' and user_id = '$temp_user_id'";
   if(mysqli_query($con, $query)){

   }else{
      echo mysqli_error($con);
   }
}

if(isset($_SESSION['org_id'])){
   $result = mysqli_query($con, "SELECT org_name, org_desc from organizations where org_id = ${_SESSION['org_id']}")->fetch_assoc();
   $org_name = $result['org_name'];
   $org_desc = $result['org_desc'];
   $points = mysqli_query($con, "SELECT curr_points from driver_to_org where driver_id = ${_SESSION['id']} and org_id = ${_SESSION['org_id']}")->fetch_assoc()['curr_points'];
}

?>
<!DOCTYPE html>
<html>
   <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Home</title>
   <link rel="stylesheet" href="css/driver_home_style.css">
   <script src="js/driver_home_page.js"></script>
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

            <li style="margin-left:auto;">
               <form action="driver_home_page.php" method="POST">
                  <select name="org_id" onchange="this.form.submit()">
                     <option value="none" selected disabled hidden>Change Organization</option>
                     <?php
                        $result = mysqli_query($con, "SELECT org_id from driver_to_org where driver_id = {$_SESSION['id']}");
                        for($i = 0; $i<$result->num_rows;$i++){
                           $id = $result->fetch_assoc()['org_id'];
                           $name = mysqli_query($con, "SELECT org_name from organizations where org_id = {$id}")->fetch_assoc()['org_name'];
                           echo("<option value=\"{$id}\">$name</option>");
                        }
                     ?>
                  </select>
               </form>
            </li>
            <li><button onclick="myFunction()">Toggle dark mode</button><script>
               function myFunction() {
                  var element = document.body;
                  element.classList.toggle("dark-mode");
               }
            </script>
            </li>
         </div>
      </ul>
   </nav>

   <h1>My Organization: <?php echo("$org_name");?></h1>
   <h2><?php echo("$org_desc");?></h2>



   <div class="content">
      <div class="base">
         <label class="indent">My Wishlist: </label>
         <div id="catalog" class="scroll_box">
            <ul id="catalog_listing" class="scroll_list">

               <?php



                  $query_user_id = $_SESSION['id'];
                  $query = "SELECT * FROM wishlist WHERE user_id = '$query_user_id'";
                  $result = mysqli_query($con, $query);
                  if(is_object($result)){
                     for($x = 0; $x < $result->num_rows; $x++){
                        $row = $result->fetch_assoc();
                        $temp_prod_id = $row['product_id'];
                        $query2 = "SELECT * FROM products WHERE product_id = '$temp_prod_id'";
                        $result2 = mysqli_query($con, $query2);
                        if(is_object($result2)){
                           if($result2->num_rows == 1){
                              $row2 = $result2->fetch_assoc();
                              $item_points = $row2['points'];
                              $item_price = $item_points * $conversionRate;
                              $img_src = $row2['image_src'];
                              $title = $row2['title'];
                              $id = $temp_prod_id;
                              echo "
                                 <li class='catalog_entry' price='$item_price' product_id='$id' popularity='$popularity'>
                                    <img src='$img_src' width='50px' height='50px'>
                                    <p>$item_price Points </p>
                                    <p>$title</p>
                                    <form action='wishlist.php' method='post'>
                                       <input type='hidden' id='add_to_wishlist' name='remove_from_wishlist' value='0'>
                                       <input type='hidden' id='img_src' name='img_src' value='$img_src'>
                                       <input type='hidden' id='title' name='title' value='$title'>
                                       <input type='hidden' id='id' name='id' value='$id'>
                                       <input type='hidden' id='item_points' name='item_points' value='$item_price'>
                                       <button type='submit' style='margin-top:20%;'>Remove from Wishlist</button>
                                    </form>
                                 </li>
                              ";
                           }
                        }
                     }
                  }
               ?>
               
            </ul>
         </div>
      </div>
   </div>
</body>
</html>