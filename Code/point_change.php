<?php
require_once "Database/config.php";
header('Location: home_page.php');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $point_change = $_POST['point_change'];
    $driver_id = $_POST['driver_id'];
    $sponsor_id = $_SESSION['id'];
    $reason = $_POST['reason'];
    mysqli_query($con, "call change_points($driver_id, $sponsor_id, {$_SESSION['org_id']}, $point_change, \"$reason\")");
    //echo("call change_points($driver_id, $sponsor_id, $point_change, \"$reason\")");
}
?>