<?php
require_once "Database/config.php";

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
         <label class="indent">My Orders: </label>
         <div id="catalog" class="scroll_box">
            <ul id="catalog_listing" class="scroll_list">
               <?php
                  $query_user_id = $_SESSION['id'];
                  $query_org_id = $_SESSION['org_id'];
                  $query = "SELECT * FROM orders WHERE org_id = '$query_org_id' and user_id = $query_user_id";
                  $result = mysqli_query($con, $query);
                  if(is_object($result)){
                     for($x = 0; $x < $result->num_rows; $x++){
                        $row = $result->fetch_assoc();
                        $item_price = $row['points'];
                        $id = $row['item'];
                        $date_ordered = $row['ordered'];
                        $timestamp = strtotime($row['ordered']);
                        if(strtotime("today") < strtotime("+3 days", $timestamp)){
                           $query2 = "SELECT * FROM products WHERE product_id = '$id'";
                           $res2 = mysqli_query($con, $query2);
                           if(is_object($res2)){
                              if($res2->num_rows == 1){
                                 $row2 = $res2->fetch_assoc();
                                 $img_src = $row2['image_src'];
                                 $title = $row2['title'];
                                
   
                                 $day = date('d', $timestamp) + 3;
                                 $month = date('m', $timestamp);
                                 $year = date('Y', $timestamp);
                                 $eta = $month."/".$day."/".$year;
                                 echo "
                                 <li class='catalog_entry' price='$item_price' product_id='$id' date_ordered='$date_ordered'>
                                    <img src='$img_src' width='50px' height='50px'>
                                    <p>$item_price Points </p>
                                    <p>$title</p>
                                    <p>Estimated Arrival: $eta</p>
                                 </li>
                              ";
                              }
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