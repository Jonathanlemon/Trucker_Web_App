<?php
require_once "Database/config.php";

$sql2="SELECT org_name FROM organizations where 1";
$result=mysqli_query($con, $sql2);

while ($row= mysqli_fetch_assoc($result)) {
    $countOrg = 1;
    $org_name=$row["org_name"];
    $id=$row["org_id"];
    $options.="<OPTION VALUE=\"$countOrg\">".$org_name.'</option>';
    // $_POST['org'] = $options;
    $countOrg++;
}
$_POST["dateFrom"];
$_POST["dateTo"];
if(isset($_POST['type'])) {
       switch($_POST['type']) {
           case 1:
               header("Location: salesBySponsor.php");
               break;
           case 2:
               header("Location: auditLogReports.php");
               break;
       }
   }
   if(isset($_POST['org'])) {
      switch($_POST['org']) {
          case 1:
               $_SESSION['organization'] = "All Sponsor Organizations";
               $_SESSION['dateFrom'] = $_POST["dateFrom"];
               $_SESSION['dateTo'] = $_POST["dateTo"];
               break;
          case 2:
               $_SESSION['organization'] = "Publix";
               $_SESSION['dateFrom'] = $_POST["dateFrom"];
               $_SESSION['dateTo'] = $_POST["dateTo"];
               break;
          case 3:
            case 2:
               $_SESSION['organization'] = "Costco";
               $_SESSION['dateFrom'] = $_POST["dateFrom"];
               $_SESSION['dateTo'] = $_POST["dateTo"];
               break;
      }
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
            <div class="container">
            <div class="form-item"> 
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
               From:
               <input type="date" name="dateFrom" value="<?php echo date('Y-m-d'); ?>" />
                   To:
               <input type="date" name="dateTo" value="<?php echo date('Y-m-d'); ?>" />
            <br>
            <select name="type" id="type">
                <option value="">Select which Report you'd like to view as an Admin </option>
                <option value="1">Sales by Sponsor</option>
                <option value="2">Audit Log Reports</option>
            </select>
            <br>
            <select name="org" id="org">
               <option value="0">Select A Sponsor or All Sponsors for report generation </option>
               <option value="1">All Sponsor Organizations</option>
               <?php echo $options;?>
            </select>
            <div class="form-btns">
               <input type="submit" value="Generate Report" />
            <br>
        </div>
        </form>
    </div>
</body>
</html>