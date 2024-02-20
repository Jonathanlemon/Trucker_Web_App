<?php
require_once "Database/config.php";

if(!isset($_SESSION['role'])){
    header("location: login.php");
    if($_SESSION['role'] != "admin"){
        header("location: home_page.php");
    }
}

    if(isset($_POST['type']) AND isset($_POST['org_id'])) {
        if($_POST['type'] != "empty" && $_POST['org_id'] != "empty"){
            $_SESSION['org_id'] = $_POST['org_id'];
            $_SESSION['remote_view'] = 1;
            switch($_POST['type']) {
                case 1:
                    header("Location: sponsor_home_page.php");
                    break;
                case 2:
                    header("Location: driver_home_page.php");
                    break;
            }
        }
    }
?>
<!DOCTYPE html>
<head>
        <meta charset="utf-8">
        <title>Admin View Other Homepages</title>
        <link rel="stylesheet" href="css/admin_home_style.css">
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
        <form method="post" enctype="multipart/form-data">
    <div class="container">
        <div class="form-item"> 
            <select name="type" id="type">
                <option value="empty">Select which Homepage you want to view as an Admin </option>
                <option value="1"><a href="adminViewAsSponsor.php">View Sponsor Homepage</a></option>
                <option value="2"><a href="adminViewAsDriver.php">View Driver Homepage</a></option>
            </select>
            <select name="org_id" id="org">]
                <option value="empty">Select which Organization to view</option>
                <?php
                $orgs = mysqli_query($con, "SELECT * from organizations");
                for($x=0;$x<$orgs->num_rows;$x++){
                    $row = $orgs->fetch_assoc();
                    echo("<option value=\"{$row['org_id']}\"><a href=\"adminViewAsSponsor.php\">{$row['org_name']}</a></option>");
                }
                ?>
            </select>
        </div>
        <button>View Page</button>
        </form>
    </div>
</body>
</html>