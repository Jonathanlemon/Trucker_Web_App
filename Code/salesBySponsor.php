<?php
require_once "Database/config.php";
    
    $dateTo = $_SESSION['dateTo'];
    $dateFrom = $_SESSION['dateFrom'];

    $sql="SELECT * FROM orders where ordered < '$dateTo' AND ordered > '$dateFrom'";
    $result=mysqli_query($con, $sql);

    while ($row= mysqli_fetch_assoc($result)) {
        $order_id=$row["order_id"];
        $user_id=$row["user_id"];
        $org_id=$row["org_id"];
        $item=$row["item"];
        $ordered=$row["ordered"];
        $points=$row["points"];
        $options.=" <b>Order_id:</b> ".$order_id."&nbsp &nbsp &nbsp"." <b>Time Stamp:</b> "."&nbsp &nbsp &nbsp".$ordered."&nbsp &nbsp &nbsp"." <b>user_id: </b> "."&nbsp &nbsp &nbsp".$user_id."&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp"." <b>Org_id: </b> "."&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp".$org_id." <b>Item: </b> "."&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp".$item."&nbsp &nbsp &nbsp"." <b>Points: </b> "."&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp".$points."<br>";
        // $_POST['org'] = $options;
    }
   
    $mapIDs="SELECT org_name FROM organizations where org_id= '$org_id'";
    $map=mysqli_query($con, $mapIDs);

    while ($row= mysqli_fetch_assoc($map)) {
        $org_name=$row["org_name"];
        $ids.=" <b>Organization Name:</b> ".$org_name."&nbsp &nbsp &nbsp"."&nbsp &nbsp &nbsp"."&nbsp &nbsp &nbsp"."&nbsp &nbsp &nbsp"."&nbsp &nbsp &nbsp"."&nbsp &nbsp &nbsp".
        "&nbsp &nbsp &nbsp"."&nbsp &nbsp &nbsp"."&nbsp &nbsp &nbsp"."&nbsp &nbsp";
        // $_POST['org'] = $options;
    }

    $userIds="SELECT first_name, last_name FROM users where user_id= '$user_id'";
    $user=mysqli_query($con, $userIds);

    while ($row= mysqli_fetch_assoc($user)) {
        $first_name=$row["first_name"];
        $last_name=$row["last_name"];
        $uids.=" <b>User ID:</b> "."&nbsp &nbsp &nbsp".$user_id."'s <b> First and Last Name: </b>".$first_name." ".$last_name."<br>";
        // $_POST['org'] = $options;
    }
?>

<!DOCTYPE html>
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
            <li><a href="admin_create_accounts.php">Accounts</a></li>
            <li><a href="adminViewAsSponsorOrDriver.php">Admin View Homepages</a></li>
            <li><a href="logout.php">Logout</a></li>
            </li>
            </div>
         </ul>
      </nav>
<h1> Sales by Sponsor </h1>
<p> 
    <br>
    <?php echo $options;?>
    <?php echo $ids;?>
    <?php echo $uids;?>
</p>
            </body>
</br>