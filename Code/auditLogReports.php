<?php
require_once "Database/config.php";
    
    $dateTo = $_SESSION['dateTo'];
    $dateFrom = $_SESSION['dateFrom'];

    $sql="SELECT * FROM audit_log where time_stamp < '$dateTo' AND time_stamp > '$dateFrom'";
    $result=mysqli_query($con, $sql);

    while ($row= mysqli_fetch_assoc($result)) {
        $log_id=$row["log_id"];
        $time_stamp=$row["time_stamp"];
        $category=$row["category"];
        $message=$row["message"];
        $query.=" <b>Log ID:</b> ".$log_id."&nbsp &nbsp &nbsp"." <b>Time Stamp:</b> "."&nbsp &nbsp &nbsp".$time_stamp."&nbsp &nbsp &nbsp"." <b>Category: </b> "."&nbsp &nbsp &nbsp".$category."&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp"." <b>Message: </b> "."&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp".$message."<br>";
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
<h1> Audit Log Reports</h1>
<p> 
    <br>
    <?php echo $query;?>
</p>
            </body>
</br>