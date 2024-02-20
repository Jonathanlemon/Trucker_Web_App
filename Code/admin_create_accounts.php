<?php
require_once "Database/config.php";

if(!isset($_SESSION['role'])){
    header("location: login.php");
    if($_SESSION['role'] != "admin"){
        header("location: home_page.php");
    }
}

    if(isset($_POST['type'])) {
        switch($_POST['type']) {
            case 1:
                header("Location: admin_create_driver.php");
                break;
            case 2:
                header("Location: create_sponsor.php");
                break;
            case 3:
                header("Location: admin_create_admin.php");
                break;
            case 4:
                header("Location: admin_create_organization.php");
                break;
        }
    }
?>
<!DOCTYPE html>
<head>
        <meta charset="utf-8">
        <title>Admin Create Another Account</title>
        <link rel="stylesheet" href="css/admin_home_style.css">
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
        <form method="post" enctype="multipart/form-data">
    <div class="container">
        <div class="form-item"> 
            <select name="type" id="type" onchange="this.form.submit()">
                <option value="">Select user to create</option>
                <option value="1"><a href="admin_create_driver.php">Create Driver</a></option>
                <option value="2"><a href="create_sponsor.php">Create Sponsor</a></option>
                <option value="3"><a href="admin_create_admin.php">Create Admin</a></option>
                <option value="4"><a href="admin_create_organization.php">Create Sponsor Organization</a></option>
            </select>
        </div>
        </form>
    </div>
</body>
</html>