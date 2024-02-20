<?php
require_once "Database/config.php";
header('Location: driver_applications_page.php');

if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['role'] == "driver"){
    $user_id = $_POST['driver_id'];
    $org_id = $_POST['org_id'];
    $query = "call apply_to_org($user_id, $org_id)";
    $result = mysqli_query($con, $query);
}

?>