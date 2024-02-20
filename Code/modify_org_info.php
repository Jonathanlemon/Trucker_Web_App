
<?php
// Initialize the session
 $org_name = "";
 $desc = "";
// Include config file
 require_once "Database/config.php";

 if(!isset($_SESSION['role'])){
   header("location: login.php");
}

if(($_SESSION['role'] != 'admin' AND $_SESSION['role'] != 'sponsor')){
   header("location: home_page.php");
}

$result = mysqli_query($con, "SELECT org_name from organizations where org_id = '{$_SESSION['org_id']}'")->fetch_assoc();
$org_name = $result['org_name'];

if($_SERVER['REQUEST_METHOD'] == "POST"){
    mysqli_query($con, "UPDATE organizations set org_desc = '{$_POST['description']}' where org_id = '{$_SESSION['org_id']}'");
    mysqli_query($con, "CALL manual_audit('Organization Update', 'Organization: {$org_name} updated the organization description.')");
}

$result = mysqli_query($con, "SELECT org_desc from organizations where org_id = '{$_SESSION['org_id']}'")->fetch_assoc();
$desc = $result['org_desc'];
  ?>
  <!DOCTYPE html>
     <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Modify Organization</title>
        <link rel="stylesheet" href="css/profile_style.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
        rel="stylesheet">
     </head>
     <body>
        <nav class="nav_default">
           <ul class="nav nav_listing">
              <div class="image"><img src="resources/Logo.png">
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
        <div class="card">
           <form action="/modify_org_info.php" method=post id="change_org_desc">
           <p><?php echo($org_name);?></p>
           <p><?php echo($desc);?></p>
           <label class="indent">Description:</label><p class="card_detail">
            <br>
            <input type="text" name="description" id="description" value="<?php echo $desc;?>">
           </p>
           <button id="submit_button">Submit Changes</button>
           </form>
        </div>
     </body>
  